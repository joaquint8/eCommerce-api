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
            "state" => $Product->state(),
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
                        id = :id,
                        name = :name,
                        description = :description,
                        price = :price,
                        stock = :stock,
                        state = :state,
                        creation_date = :creation_date,
                        categoryId = :categoryId,
                        deleted = :deleted
                        imageUrl = :imageUrl,
                    WHERE
                        id = :id
                ACTUALIZAR_PRODUCTO;
        
        $parameters = [
            "id" => $Product->id(),
            "name" => $Product->name(),
            "description" => $Product->description(),
            "price" => $Product->price(),
            "stock" => $Product->stock(),
            "state" => $Product->state(),
            "creation_date" => $Product->creationDate(),
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

    private function toProduct(?array $primitive): ?Product {
        if ($primitive === null) {
            return null;
        }

        return new Product(
            $primitive["id"],
            $primitive["name"],
            $primitive["description"],
            $primitive["price"],
            $primitive["stock"],
            ProductState::tryFrom($primitive["state"]) ?? ProductState::ACTIVE,
            new DateTime($primitive["creation_date"]),
            $primitive["categoryId"],
            $primitive["deleted"],
            $primitive["imageUrl"]
        );
    }
}