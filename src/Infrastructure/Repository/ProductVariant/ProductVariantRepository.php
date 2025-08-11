<?php 

namespace Src\Infrastructure\Repository\ProductVariant;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\ProductVariant;

final readonly class ProductVariantRepository extends PDOManager implements ProductVariantRepositoryInterface {
    
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
            $primitive["state"],
            $primitive["deleted"]
        );
    }

    
}

