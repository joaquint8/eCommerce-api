<?php

// Namespace que organiza el código por contexto: este archivo pertenece a la infraestructura de persistencia de órdenes
namespace Src\Infrastructure\Repository\Order;

// Servicio para buscar productos por ID, usado al reconstruir órdenes desde la base de datos
use Src\Service\Product\ProductFinderService;

// Clase base que gestiona la conexión y ejecución de consultas con PDO
use Src\Infrastructure\PDO\PDOManager;

// Entidades que representan la orden y sus ítems
use Src\Entity\Order\Order;
use Src\Entity\Order\OrderItem;
use Src\Entity\Order\OrderState;

// Excepción lanzada si un producto no se encuentra al reconstruir una orden
use Src\Exception\Product\ProductNotFoundException;

// Repositorio que maneja la creación, consulta y actualización de órdenes en la base de datos
final readonly class OrderRepository extends PDOManager implements OrderRepositoryInterface {
    private ProductFinderService $productFinder;

    public function __construct()
    {
        // Inicializamos la conexión PDO y el servicio para buscar productos
        parent::__construct();
        $this->productFinder = new ProductFinderService();
    }

    // Crea una nueva orden en la base de datos y retorna su ID
    // public function create(Order $order): int
    // {
    //     // Consulta SQL para insertar la orden principal
    //     $query = <<<SQL
    //         INSERT INTO Orders (external_reference, total, shipping_address, status, created_at)
    //         VALUES (:external_reference, :total, :shipping_address, :status, :created_at)
    //     SQL;

    //     // Parámetros extraídos de la entidad Order
    //     $params = [
    //         "external_reference" => $order->externalReference(),
    //         "total" => $order->total(),
    //         "shipping_address" => $order->shipping_address(),
    //         "status" => $order->status(),
    //         "created_at" => $order->created_at()->format("Y-m-d H:i:s")
    //     ];

    //     // Ejecutamos la inserción
    //     $this->execute($query, $params);

    //     // Obtenemos el ID autogenerado por la base de datos
    //     $orderId = (int) $this->lastInsertId();

    //     // Insertamos cada ítem de la orden en la tabla Order_Detail
    //     foreach ($order->items() as $item) {
    //         $this->insertDetail($orderId, $item);
    //     }

    //     return $orderId;
    // }

    public function create(Order $order): int {
        $sql = "
            INSERT INTO Orders (
                external_reference,
                total,
                shipping_address,
                status,
                created_at
            ) VALUES (
                :external_reference,
                :total,
                :shipping_address,
                :status,
                :created_at
            )
        ";

        $params = [
            ':external_reference' => $order->externalReference(),
            ':total' => $order->total(),
            ':shipping_address' => $order->shipping_address(),
            ':status' => $order->status()->value, // convierte enum a string
            ':created_at' => $order->created_at()->format('Y-m-d H:i:s')
        ];

        $this->execute($sql, $params);

        return (int)$this->lastInsertId();
    }


    // Inserta un ítem de la orden en la tabla Order_Detail
    private function insertDetail(int $orderId, OrderItem $item): void
    {
        $query = <<<SQL
            INSERT INTO Order_Detail (order_id, product_id, quantity, unit_price, total_price)
            VALUES (:order_id, :product_id, :quantity, :unit_price, :total_price)
        SQL;

        $params = [
            "order_id" => $orderId,
            "product_id" => $item->productId(),
            "quantity" => $item->quantity(),
            "unit_price" => $item->price(),
            "total_price" => $item->quantity() * $item->price() // Calculamos el total por ítem
        ];

        $this->execute($query, $params);
    }

    // Busca una orden por su external_reference
    public function findByExternalReference(string $reference): ?Order
    {
        $query = <<<SQL
            SELECT * FROM Orders WHERE external_reference = :reference LIMIT 1
        SQL;

        // Ejecutamos la consulta
        $raw = $this->fetchOne($query, ["reference" => $reference]);

        // Si no se encuentra o no está en estado "pending", devolvemos null
        if (!$raw || $raw["status"] !== "pending") {
            return null;
        }

        // Si se encuentra, reconstruimos la entidad Order
        return $this->toOrder($raw);
    }

    // Actualiza el estado de una orden según su external_reference
    public function updateStatusByReference(string $externalReference, string $newStatus): bool {
        $query = "UPDATE Orders SET status = :status WHERE external_reference = :reference";
        $params = [
            "status" => $newStatus,
            "reference" => $externalReference
        ];

        // Log para depuración
        error_log("Ejecutando UPDATE: status={$newStatus}, reference={$externalReference}");

        $stmt = $this->execute($query, $params);

        // Verificamos si se afectó alguna fila
        if ($stmt instanceof \PDOStatement) {
            $rows = $stmt->rowCount();
            error_log("Filas afectadas: {$rows}");
            return $rows > 0;
        }

        error_log("Error al ejecutar el UPDATE");
        return false;
    }

    public function find(int $id): ?Order {
        $query = <<<SQL
            SELECT *
            FROM Orders O
            WHERE O.id = :id
        SQL;

        $parameters = [
            "id" => $id,
        ];

        $result = $this->execute($query, $parameters);

        return $this->toOrder($result[0] ?? null);
    }
    
    /** @return Order[] */
    public function search(): array{
        
        $query = <<<OBTENER_PEDIDOS
        SELECT
        *
        FROM
        Orders O
        
        OBTENER_PEDIDOS;
        
        $results = $this->execute($query);//Método heredado de PDOManager.Retorna un array asociativo con los resultados
        
        $Order = [];
        foreach($results as $result) { //Convierte cada resultado en un objeto Order
            $Order[] = $this->toOrder($result);
        }
        
        return $Order;
    }
    
    private function toOrder(array $raw): Order
    {
        // Consultamos los ítems asociados a la orden
        $items = $this->execute(
            "SELECT * FROM Order_Detail WHERE order_id = :order_id",
            ["order_id" => $raw["id"]]
        );

        $orderItems = [];

        foreach ($items as $item) {
            try {
                // Buscamos el producto para validar que existe
                $product = $this->productFinder->find((int) $item["product_id"]);
            } catch (ProductNotFoundException $e) {
                // Si no se encuentra el producto, lanzamos una excepción
                throw new \RuntimeException("Producto no encontrado: " . $item["product_id"]);
            }

            // Creamos el objeto OrderItem con los datos del producto
            $orderItems[] = new OrderItem(
                $product->id(),
                (int) $item["quantity"],
                (float) $item["unit_price"],
            );
        }

        // Retornamos la entidad Order reconstruida
        return new Order(
            (int) $raw["id"],
            $raw["external_reference"],
            (float) $raw["total"],
            $raw["shipping_address"],
            OrderState::from($raw["status"]),
            new \DateTime($raw["created_at"]),
            $orderItems
        );
    }
}
