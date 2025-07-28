<?php 

namespace Src\Infrastructure\Repository\Category;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Category\Category;
use DateTime;

final readonly class CategoryRepository extends PDOManager implements CategoryRepositoryInterface {
    public function find(int $id): ?Category
    {
        $query = <<<HEREDOC
                        SELECT 
                            *
                        FROM
                            category C
                        WHERE
                            C.id = :id
                        AND
                            C.deleted = 0
                    HEREDOC;

        $parameters = [
            "id" => $id,
        ];

        $result = $this->execute($query, $parameters);

        return $this->toCategory($result[0] ?? null);
    }

    /** @return Category[] */
    public function search(): array
    {
        $query = <<<HEREDOC
                        SELECT
                            *
                        FROM
                            category C
                        WHERE
                            C.deleted = 0
                    HEREDOC;
        
        $results = $this->execute($query);

        $categories = [];
        foreach($results as $result) {
            $categories[] = $this->toCategory($result);
        }

        return $categories;
    }
    public function insert(Category $category): void
    {
        $query = "INSERT INTO Category (name, deleted) VALUES (:name, :deleted) ";

        $parameters = [
            "name" => $category->name(),
            "deleted" => $category->isDeleted()
        ];

        $this->execute($query, $parameters);
    }

    private function toCategory(?array $primitive): ?Category {
        if ($primitive === null) {
            return null;
        }

        return new Category(
            $primitive["id"],
            $primitive["name"],
            $primitive["description"],
            new DateTime($primitive["creation_date"]),
            $primitive["deleted"]
        );
    }

    // private function toCategory(?array $primitive): ?Category {
    //     if (!is_array($primitive) || !array_key_exists("id", $primitive)) {
    //         return null; // o lanzar una excepción si preferís ir más estricto
    //     }

    //     return new Category(
    //         $primitive["id"],
    //         $primitive["name"] ?? '',
    //         $primitive["description"] ?? '',
    //         new \DateTime($primitive["creation_date"] ?? 'now'),
    //         (bool)($primitive["deleted"] ?? false)
    //     );
    // }

}