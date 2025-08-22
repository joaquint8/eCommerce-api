<?php

// Namespace que organiza el código por contexto: este archivo pertenece al servicio de pagos
namespace Src\Service\Payment;

// Importamos el proveedor que se encarga de generar la preferencia en Mercado Pago
use Src\Infrastructure\Provider\Payment\MercadoPagoProvider;

// Importamos el repositorio que maneja la persistencia de órdenes en la base de datos
use Src\Infrastructure\Repository\Order\OrderRepository;

// Importamos las entidades que representan la orden y sus ítems
use Src\Entity\Order\Order;
use Src\Entity\Order\OrderItem;
use Src\Entity\Order\OrderState;

// Para registrar la fecha de creación de la orden
use DateTime;

// Servicio que encapsula toda la lógica para crear una orden y generar una preferencia de pago
final readonly class PaymentCreatorService {
    private MercadoPagoProvider $provider;         // Proveedor de integración con Mercado Pago
    private OrderRepository $orderRepository;      // Repositorio para guardar y consultar órdenes

    public function __construct() {
        // Inicializamos las dependencias necesarias
        $this->provider = new MercadoPagoProvider();
        $this->orderRepository = new OrderRepository();
    }

    // Método principal que crea la orden y genera la preferencia de pago
    public function createPayment(
        array $items,              // Lista de productos que el usuario quiere comprar
        array $payer,              // Información del pagador (nombre, email, etc.)
        string $shippingAddress,   // Dirección de envío
        string $externalReference, // Identificador único para rastrear la orden
        float $total,              // Monto total de la compra
        int $userId                // ID del usuario que realiza el pago
    ): array {
        // Convertimos los ítems crudos en objetos tipados OrderItem
        $orderItems = [];

        foreach ($items as $item) {
            // Validamos que cada ítem tenga los campos necesarios
            if (!isset($item["product_id"], $item["quantity"], $item["price"])) {
                throw new \InvalidArgumentException("Todos los ítems deben tener product_id, quantity y price");
            }

            // Creamos un objeto OrderItem con tipado fuerte
            $orderItems[] = new OrderItem(
                (int) $item["product_id"],
                (int) $item["quantity"],
                (float) $item["price"]
            );
        }

        

        // Creamos la entidad Order con todos los datos
        $order = new Order(
            (int) $userId,              // ID del usuario
            $externalReference,         // Referencia única para trazabilidad
            $total,                     // Monto total
            $shippingAddress,           // Dirección de envío
            $status = OrderState::from('pending'),                  // Estado inicial de la orden
            new DateTime(),             // Fecha de creación
            $orderItems                 // Lista de ítems como objetos tipados
        );

        // Verificamos si ya existe una orden con ese externalReference
        $orderExistente = $this->orderRepository->findByExternalReference($externalReference);

        if ($orderExistente !== null) {
            // Si ya existe, evitamos duplicarla y generamos la preferencia sobre esa orden
            error_log("Orden existente encontrada. Generando preferencia para OrderID {$orderExistente->id()} con ExternalReference $externalReference");

            return [
                "preference" => $this->provider->generatePreference(
                    $items,                          // Ítems originales
                    $payer,                          // Datos del pagador
                    (string) $orderExistente->id(), // ID de la orden existente
                    (string) $userId,                // ID del usuario
                    $externalReference               // Referencia única
                ),
                "order_id" => $orderExistente->id() // Retornamos el ID existente
            ];
        }

        // Insertamos la nueva orden en la base de datos
        $orderId = $this->orderRepository->create($order);

        error_log("Orden nueva creada. Generando preferencia para OrderID $orderId con ExternalReference $externalReference");

        // Generamos la preferencia de Mercado Pago con el ID recién creado
        return [
            "preference" => $this->provider->generatePreference(
                $items,
                $payer,
                (string) $orderId,
                (string) $userId,
                $externalReference
            ),
            "order_id" => $orderId // Retornamos el ID de la nueva orden
        ];
    }
}
