<?php 

namespace Src\Infrastructure\Repository\ProductImages;
use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\ProductImages;

use DateTime;

final readonly class ProductImagesRepository extends PDOManager implements ProductImagesRepositoryInterface {

    public function findImagesByProductId(int $productId): array
    {
        $query = <<<SQL
            SELECT *
            FROM Product_Images PI
            WHERE PI.product_id = :product_id
            AND PI.deleted = 0
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

    /** @return ProductImages[] */
    public function search(): array
    {
        $query = <<<OBTENER_IMAGENES
                        SELECT
                            *
                        FROM
                            Product_Images PI
                        WHERE
                            PI.deleted = 0
                    OBTENER_IMAGENES;
        
        $results = $this->execute($query);

        $images = [];
        foreach($results as $result) {
            $images[] = $this->toProductImages($result);
        }

        return $images;
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

    public function update(ProductImages $image): void {
        $query = <<<SQL
            UPDATE Product_Images
            SET
                image_url = :image_url,
                updated_at = :updated_at,
                deleted = :deleted
            WHERE id = :id
        SQL;

        $parameters = [
            "id" => $image->id(),
            "image_url" => $image->image_url(),
            "updated_at" => $image->updated_at()->format("Y-m-d H:i:s"),
            "deleted" => $image->isDeleted() ? 1 : 0
        ];

        $this->execute($query, $parameters);
    }

    public function deleteByProductId(int $productId): void {
        $query = <<<SQL
            UPDATE Product_Images
            SET deleted = 1
            WHERE product_id = :product_id
        SQL;

        $this->execute($query, ["product_id" => $productId]);
    }

    public function restoreByProductId(int $productId): void {
        $query = <<<SQL
            UPDATE Product_Images
            SET deleted = 0
            WHERE product_id = :product_id
        SQL;

        $this->execute($query, ["product_id" => $productId]);
    }

    public function physicalDeleteByProductId(int $productId): void {
        $query = <<<SQL
            DELETE FROM Product_Images
            WHERE product_id = :product_id AND deleted = 1
        SQL;

        $this->execute($query, ["product_id" => $productId]);
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