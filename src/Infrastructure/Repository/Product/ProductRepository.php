<?php 

namespace Src\Infrastructure\Repository\Product;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\Product;
use Src\Entity\Product\ProductState;
use DateTime;

final readonly class ProductRepository extends PDOManager implements ProductRepositoryInterface {
    public function find(int $id): ?Product
    {
        $query = <<<HEREDOC
                        SELECT 
                            *
                        FROM
                            Product P
                        WHERE
                            P.id = :id
                        AND
                            P.deleted = 0
                    HEREDOC;

        $parameters = [
            "id" => $id,
        ];

        $result = $this->execute($query, $parameters);

        return $this->toProduct($result[0] ?? null);
    }

    /** @return Product[] */
    public function search(): array
    {
        $query = <<<HEREDOC
                        SELECT
                            *
                        FROM
                            Product P
                        WHERE
                            P.deleted = 0
                    HEREDOC;
        
        $results = $this->execute($query);

        $products = [];
        foreach($results as $result) {
            $products[] = $this->toProduct($result);
        }

        return $products;
    }
    public function insert(Product $Product): void
    {
        $query = "INSERT INTO Product (name, description, categoryId, stock, deleted) VALUES (:name, :description, :categoryId, :stock, :deleted) ";

        $parameters = [
            "name" => $Product->name(),
            "description" => $Product->description(),
            "categoryId" => $Product->categoryId(),
            "stock" => $Product->stock(),
            "deleted" => $Product->isDeleted()
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