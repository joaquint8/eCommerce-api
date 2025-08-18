<?php 

namespace Src\Infrastructure\Repository\Product;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\Product;
use Src\Entity\Product\ProductState;
use DateTime;

final readonly class ProductRepository extends PDOManager implements ProductRepositoryInterface {
    public function find(int $id): ?Product
    {
        $query = <<<OBTENER_PRODUCTOS_POR_ID
                        SELECT 
                            *
                        FROM
                            Product P
                        WHERE
                            P.id = :id
                        AND
                            P.deleted = 0
                    OBTENER_PRODUCTOS_POR_ID;

        $parameters = [
            "id" => $id,
        ];

        $result = $this->execute($query, $parameters);

        return $this->toProduct($result[0] ?? null);
    }

    public function findDeleted(int $id): ?Product
    {
        $query = <<<OBTENER_PRODUCTOS_ELIMINADAS_POR_ID
                        SELECT 
                            *
                        FROM
                            Product P
                        WHERE
                            P.id = :id
                        AND
                            P.deleted = 1
                    OBTENER_PRODUCTOS_ELIMINADAS_POR_ID;

        $parameters = [
            "id" => $id,
        ];

        $result = $this->execute($query, $parameters);

        return $this->toProduct($result[0] ?? null);
    }

    public function findImageUrlByProductId(int $productId): ?string {
        $query = "SELECT image_url FROM Product_Images WHERE product_id = :productId LIMIT 1";
        $result = $this->execute($query, ['productId' => $productId]);

        return $result[0]['image_url'] ?? null;
    }

    public function findVariantsByProductId(int $productId): array {
        // Consulta SQL para obtener variantes activas del producto
        $query = "SELECT * FROM Product_Variants WHERE product_id = :productId AND deleted = 0";

        // Ejecutamos la consulta usando el método heredado execute()
        $rows = $this->execute($query, ['productId' => $productId]);

        // Transformamos cada fila en una instancia de ProductVariant
        return array_map(function ($row) {
            return new ProductVariant(
                (int) $row['id'],
                $row['color'],
                $row['size'],
                (int) $row['stock'],
                ProductState::tryFrom($row['state']) ?? ProductState::ACTIVE
            );
        }, $rows);
    }

    /** @return Product[] */
    public function search(): array
    {
        $query = <<<OBTENER_PRODUCTOS
                        SELECT
                            *
                        FROM
                            Product P
                        WHERE
                            P.deleted = 0
                    OBTENER_PRODUCTOS;
        
        $results = $this->execute($query);

        $products = [];
        foreach($results as $result) {
            $products[] = $this->toProduct($result);
        }

        return $products;
    }

     /** @return Product[] */
    public function searchDeleted(): array
    {
        $query = <<<OBTENER_PRODUCTOS_ELIMINADOS
                        SELECT
                            *
                        FROM
                            Product P
                        WHERE
                            P.deleted = 1
                    OBTENER_PRODUCTOS_ELIMINADOS;
        
        $results = $this->execute($query);

        $products = [];
        foreach($results as $result) {
            $products[] = $this->toProduct($result);
        }

        return $products;
    }

    //Agregar producto
    public function insert(Product $Product): void
    {
        $query = "INSERT INTO Product (name, description, price, stock, state, creation_date, categoryId, deleted, imageUrl) VALUES (:name, :description, :price, :stock, :state, :creation_date, :categoryId, :deleted, :imageUrl) ";

        $parameters = [
            "name" => $Product->name(),
            "description" => $Product->description(),
            "price" => $Product->price(),
            "stock" => $Product->stock(),
            "state" => $Product->state()->value,
            "creation_date" => $Product->creationDate()->format("Y-m-d H:i:s"),
            "categoryId" => $Product->categoryId(),
            "deleted" => $Product->isDeleted(),
            "imageUrl" => $Product->imageUrl()
        ];

        $this->execute($query, $parameters);
    }

    public function update(Product $Product): void
    {
        $query = <<<ACTUALIZAR_PRODUCTO
                    UPDATE
                        Product
                    SET
                        name = :name,
                        description = :description,
                        price = :price,
                        stock = :stock,
                        state = :state,
                        categoryId = :categoryId,
                        deleted = :deleted,
                        imageUrl = :imageUrl
                    WHERE
                        id = :id
                ACTUALIZAR_PRODUCTO;
        
        $parameters = [
            "id" => $Product->id(),
            "name" => $Product->name(),
            "description" => $Product->description(),
            "price" => $Product->price(),
            "stock" => $Product->stock(),
            "state" => $Product->state()->value,
            "categoryId" => $Product->categoryId(),
            "deleted" => $Product->isDeleted(),
            "imageUrl" => $Product->imageUrl()
        ];

        $this->execute($query, $parameters);
    }

    public function delete(Product $Product): void
    {
        $query = <<<ELIMINAR_PRODUCTO
                        DELETE FROM Product
                        WHERE id = :id AND deleted = 1
                    ELIMINAR_PRODUCTO;

        $parameters = [
            "id" => $Product->id()
        ];

        $this->execute($query, $parameters);
    }

    public function restore(Product $Product): void
    {
        $query = <<<RESTAURAR_PRODUCTO
                        UPDATE Product
                        SET deleted = 0
                        WHERE id = :id AND deleted = 1
                    RESTAURAR_PRODUCTO;

        $parameters = [
            "id" => $Product->id()
        ];

        $this->execute($query, $parameters);
    }

    public function toProduct(array $row): Product {
        // Validación defensiva
        if (!isset($row['id'], $row['name'], $row['description'], $row['price'], $row['categoryId'], $row['deleted'], $row['created_at'])) {
            throw new \InvalidArgumentException('Faltan campos obligatorios en Product');
        }

        return new Product(
            (int) $row['id'],
            $row['name'],
            $row['description'],
            (float) $row['price'],
            0, // stock por defecto
            ProductState::ACTIVE, // estado por defecto
            new DateTime($row['created_at']),
            (int) $row['categoryId'],
            (int) $row['deleted'],
            null // imageUrl opcional
        );
    }
}