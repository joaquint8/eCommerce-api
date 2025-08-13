<?php 

namespace Src\Infrastructure\Repository\ProductVariant;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\ProductVariant;
use Src\Entity\Product\ProductState;

final readonly class ProductVariantRepository extends PDOManager implements ProductVariantRepositoryInterface {

    public function findVariantsByProductId(int $productId): array
    {
        $query = <<<SQL
            SELECT *
            FROM Product_Variants PV
            WHERE PV.product_id = :product_id
            AND PV.deleted = 0
        SQL;

        $parameters = [
            "product_id" => $productId,
        ];

        $results = $this->execute($query, $parameters);

        $variants = [];
        foreach ($results as $row) {
            $variants[] = $this->toProductVariant($row);
        }

        return $variants;
    }
    
    public function insert(ProductVariant $ProductVariant): void
    {
        $query = "INSERT INTO Product_Variants (product_id, color, size, stock, state, deleted) VALUES (:product_id, :color, :size, :stock, :state, :deleted) ";

        $parameters = [
            "product_id" => $ProductVariant->productId(),
            "color" => $ProductVariant->color(),
            "size" => $ProductVariant->size(),
            "stock" => $ProductVariant->stock(),
            "state" => $ProductVariant->state()->value,
            "deleted" => $ProductVariant->isDeleted()
        ];

        $this->execute($query, $parameters);
    }

    private function toProductVariant(?array $primitive): ?ProductVariant
    {
        if ($primitive === null) {
            return null;
        }

        return new ProductVariant(
            $primitive["id"],
            $primitive["product_id"],
            $primitive["color"],
            $primitive["size"],
            $primitive["stock"],
            ProductState::from($primitive["state"]),
            $primitive["deleted"]
        );
    }

    
}

