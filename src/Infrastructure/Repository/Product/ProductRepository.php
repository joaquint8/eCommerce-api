<?php 

namespace Src\Infrastructure\Repository\Product;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\Product;
use Src\Entity\Product\ProductVariant;
use Src\Entity\Product\ProductState;
use Src\Entity\Product\ProductImages;

use DateTime;

final readonly class ProductRepository extends PDOManager implements ProductRepositoryInterface {
    public function find(int $id): ?Product {
        $query = <<<SQL
            SELECT * FROM Product P WHERE P.id = :id AND P.deleted = 0
        SQL;

        $parameters = ["id" => $id];
        $result = $this->execute($query, $parameters);

        if (empty($result)) {
            return null;
        }

        // // Traer variantes
        // $variantsQuery = "SELECT * FROM product_variants WHERE product_id = :productId AND deleted = 0";
        // $variantsResult = $this->execute($variantsQuery, ['productId' => $id]);

        // // Traer imagenes
        // $imagesQuery = "SELECT * FROM Product_Images WHERE product_id = :productId AND deleted = 0";
        // $imagesResult = $this->execute($imagesQuery, ['productId' => $id]);

        // $variants = [];
        // $images = [];
        // foreach ($variantsResult as $v) {
        //     $variants[] = new ProductVariant(
        //         $v['id'],
        //         $v['product_id'],
        //         $v['color'],
        //         $v['size'],
        //         $v['stock'],
        //         ProductState::from($v['state']),
        //         $v['deleted']
        //     );
        // }

        // foreach ($imagesResult as $image) {
        //     $images[] = new ProductImages(
        //         $image['id'],
        //         $image['product_id'],
        //         $image['image_url'],
        //         new DateTime($image['created_at']),
        //         new DateTime($image['updated_at']),
        //         $image['deleted']
        //     );
        // }

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

     

    //Agregar producto
    public function insert(Product $Product): int
    {
        $query = "INSERT INTO Product (name, description, price, categoryId, deleted, created_at, updated_at) VALUES (:name, :description, :price, :categoryId, :deleted, :created_at, :updated_at) ";

        $parameters = [
            "name" => $Product->name(),
            "description" => $Product->description(),
            "price" => $Product->price(),
            "categoryId" => $Product->categoryId(),
            "deleted" => $Product->isDeleted(),
            "created_at" => $Product->created_at()->format("Y-m-d H:i:s"),
            "updated_at" => $Product->updated_at()->format("Y-m-d H:i:s")
        ];

        $this->execute($query, $parameters);

        // retorna el ID generado por la BD
        return (int) $this->lastInsertId();
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

