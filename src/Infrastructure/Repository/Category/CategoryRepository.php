<?php 

namespace Src\Infrastructure\Repository\Category;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Category\Category;
use DateTime;

final readonly class CategoryRepository extends PDOManager implements CategoryRepositoryInterface {
    public function find(int $id): ?Category //puede retornar un objeto Category o null
    {
        $query = <<<OBTENER_CATEGORIAS_POR_ID
                        SELECT 
                            *
                        FROM
                            Category C
                        WHERE
                            C.id = :id
                        AND
                            C.deleted = 0
                    OBTENER_CATEGORIAS_POR_ID;

        $parameters = [
            "id" => $id,
        ];

        $result = $this->execute($query, $parameters);

        return $this->toCategory($result[0] ?? null);
    }

    

    /** @return Category[] */
    public function search(): array{

        $query = <<<OBTENER_CATEGORIAS
                        SELECT
                            *
                        FROM
                            Category C
                        WHERE
                            C.deleted = 0
                    OBTENER_CATEGORIAS;
        
        $results = $this->execute($query);//Método heredado de PDOManager.Retorna un array asociativo con los resultados

        $Category = [];
        foreach($results as $result) { //Convierte cada resultado en un objeto Category
            $Category[] = $this->toCategory($result);
        }

        return $Category;
    }

    
    //Agregar categorias
    public function insert(Category $Category): void
    {
        $query = "INSERT INTO Category (name, description, creation_date, deleted) VALUES (:name, :description, :creation_date, :deleted)";

        $parameters = [//se pasan los datos del objeto instanciado con getters
            "name" => $Category->name(),
            "description" => $Category->description(),
            "creation_date" => $Category->creationDate()->format("Y-m-d H:i:s"),
            "deleted" => $Category->isDeleted()
        ];

        $this->execute($query, $parameters);
    }

    public function update(Category $Category): void
    {
        $query = <<<ACTUALIZAR_CATEGORIA
                    UPDATE
                        Category
                    SET
                        id = :id,
                        name = :name,
                        description = :description,
                        deleted = :deleted
                    WHERE
                        id = :id
                ACTUALIZAR_CATEGORIA;
        
        $parameters = [//se pasan los datos de Category con getters
            "id" => $Category->id(),
            "name" => $Category->name(),
            "description" => $Category->description(),
            "deleted" => $Category->isDeleted()
        ];

        $this->execute($query, $parameters);
    }

    public function delete(Category $Category): void
    {
        $query = <<<ELIMINAR_CATEGORIA
                        DELETE FROM Category
                        WHERE id = :id AND deleted = 1
                    ELIMINAR_CATEGORIA;

        $parameters = [
            "id" => $Category->id()
        ];

        $this->execute($query, $parameters);
    }

    
    
    //Convierte arrays DB → Objetos Category
    private function toCategory(?array $primitive): ?Category {
        if ($primitive === null) {
            return null;
        }

        return new Category(
            $primitive["id"],
            $primitive["name"],
            new DateTime($primitive["created_at"]),
            new DateTime($primitive["updated_at"]),
            $primitive["deleted"]
        );
    }
}