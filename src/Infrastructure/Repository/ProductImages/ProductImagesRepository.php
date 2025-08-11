<?php 

namespace Src\Infrastructure\Repository\ProductImages;
use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\ProductImages;

use DateTime;

final readonly class ProductImagesRepository extends PDOManager implements ProductImagesRepositoryInterface {

    public function findByProductId(int $productId): array
    {
        $query = <<<SQL
            SELECT *
            FROM Product_Images I
            WHERE I.product_id = :product_id
            AND I.deleted = 0
        SQL;

        $parameters = [
            "product_id" => $productId,
        ];

        $results = $this->execute($query, $parameters);

        $images = [];
        foreach ($results as $row) {
            $images[] = $this->toProductImages($row);
        }

        return $images;
    }

    public function find(int $id): ?ProductImages
    {
        $query = <<<OBTENER_IMAGENES_POR_ID
                        SELECT 
                            *
                        FROM
                            Product_Images I
                        WHERE
                            I.id = :id
                        AND
                            I.deleted = 0
                    OBTENER_IMAGENES_POR_ID;

        $parameters = [
            "id" => $id,
        ];

        $result = $this->execute($query, $parameters);

        return $this->toProductImages($result[0] ?? null);
    }
    
    public function insert(ProductImages $ProductImages): void
    {
        $query = "INSERT INTO Product_Images(product_id, image_url, created_at, updated_at, deleted) VALUES (:product_id, :image_url, :created_at, :updated_at, :deleted) ";

        $parameters = [
            "product_id" => $ProductImages->productId(),
            "image_url" => $ProductImages->image_url(),
            "created_at" => $ProductImages->created_at()->format("Y-m-d H:i:s"),
            "updated_at" => $ProductImages->updated_at()->format("Y-m-d H:i:s"),
            "deleted" => $ProductImages->isDeleted()
        ];

        $this->execute($query, $parameters);
    }

    private function toProductImages(?array $primitive): ?ProductImages
    {
        if ($primitive === null) {
            return null;
        }

        return new ProductImages(
            $primitive["id"],
            $primitive["product_id"],
            $primitive["image_url"],
            new DateTime($primitive["created_at"]),
            new DateTime($primitive["updated_at"]),
            $primitive["deleted"]
        );
    }

    
}

