<?php

namespace Src\Infrastructure\Repository\Product;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\Product;

use DateTime;

final readonly class ProductRepository extends PDOManager implements ProductRepositoryInterface {
    public function find(int $id): ?Product {
        $query = <<<OBTENER_PRODUCTO_POR_ID
            SELECT * FROM Product P WHERE P.id = :id AND P.deleted = 0
        OBTENER_PRODUCTO_POR_ID;

        $parameters = ["id" => $id];
        $result = $this->execute($query, $parameters);

        if (empty($result)) {
            return null;
        }

        return $this->toProduct($result[0] ?? null);
    }

    public function findDeleted(int $id): ?Product {
        $query = <<<OBTENER_PRODUCTO_ELIMINADO_POR_ID
            SELECT * FROM Product P WHERE P.id = :id AND P.deleted = 1
        OBTENER_PRODUCTO_ELIMINADO_POR_ID;

        $parameters = ["id" => $id];
        $result = $this->execute($query, $parameters);

        if (empty($result)) {
            return null;
        }

        return $this->toProduct($result[0] ?? null);
    }

    public function insertAndReturnId(Product $product): int {
        $query = "INSERT INTO Product (name, description, price, categoryId, deleted, created_at, updated_at)
                VALUES (:name, :description, :price, :categoryId, :deleted, :created_at, :updated_at)";

        $parameters = [
            "name" => $product->name(),
            "description" => $product->description(),
            "price" => $product->price(),
            "categoryId" => $product->categoryId(),
            "deleted" => $product->isDeleted(),
            "created_at" => $product->created_at()->format("Y-m-d H:i:s"),
            "updated_at" => $product->updated_at()->format("Y-m-d H:i:s")
        ];

        $this->execute($query, $parameters);

        return (int) $this->lastInsertId(); // método heredado de PDOManager
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

    public function update(Product $Product): void
    {
        $query = <<<ACTUALIZAR_PRODUCTO
                    UPDATE
                        Product
                    SET
                        id = :id,
                        name = :name,
                        description = :description,
                        price = :price,
                        categoryId = :categoryId,
                        deleted = :deleted,
                        updated_at = :updated_at
                    WHERE
                        id = :id
                ACTUALIZAR_PRODUCTO;
        
        $parameters = [//se pasan los datos de Category con getters
            "id" => $Product->id(),
            "name" => $Product->name(),
            "description" => $Product->description(),
            "price" => $Product->price(),
            "categoryId" => $Product->categoryId(),
            "deleted" => $Product->isDeleted(),
            "updated_at" => $Product->updated_at()->format("Y-m-d H:i:s")
        ];

        $this->execute($query, $parameters);
    }

    public function restore(Product $Product): void
    {
        $query = <<<RESTORE_PRODUCTO
                    UPDATE
                        Product
                    SET
                        deleted = 0
                    WHERE
                        id = :id
                RESTORE_PRODUCTO;

        $parameters = [
            "id" => $Product->id()
        ];

        $this->execute($query, $parameters);
    }

    public function physicalDelete(Product $Product): void
    {
        $query = <<<ELIMINAR_PRODUCTO
                    DELETE FROM
                        Product
                    WHERE
                        id = :id AND deleted = 1
                ELIMINAR_PRODUCTO;

        $parameters = [
            "id" => $Product->id()
        ];

        $this->execute($query, $parameters);
    }

    public function insert(Product $product): void {
    $query = <<<SQL
        INSERT INTO Product (name, description, price, categoryId, deleted, created_at, updated_at)
        VALUES (:name, :description, :price, :categoryId, 0, NOW(), NOW())
    SQL;

    $parameters = [
        "name" => $product->name(),
        "description" => $product->description(),
        "price" => $product->price(),
        "categoryId" => $product->categoryId()
    ];

    $this->execute($query, $parameters);

    // Obtener el ID generado
    $productId = (int) $this->lastInsertId();
    $product->setId($productId);

    // Delegar persistencia de variantes
    $variantRepo = new \Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository();
    foreach ($product->getVariants() as $variant) {
        $variant->setProductId($productId);
        $variantRepo->insert($variant);
    }

    // Delegar persistencia de imágenes
    $imageRepo = new \Src\Infrastructure\Repository\ProductImages\ProductImagesRepository();
    foreach ($product->getImages() as $image) {
        $image->setProductId($productId);
        $imageRepo->insert($image);
    }
}


    private function toProduct(?array $primitive): ?Product 
    {
        if ($primitive === null) {
            return null;
        }

        return new Product(
            $primitive["id"],
            $primitive["name"],
            $primitive["description"],
            $primitive["price"],
            $primitive["categoryId"],
            $primitive["deleted"],
            new DateTime($primitive["created_at"]),
            new DateTime($primitive["updated_at"]),
            //$variants,
            //$images
        );
    }

    
}

