<?php 

namespace Src\Infrastructure\Repository\ProductVariant;

use Src\Entity\Product\ProductVariant;

interface ProductVariantRepositoryInterface {
    public function findVariantsByProductId(int $id): ?array;

    /** @return ProductVariant[] */
    //public function search(): array;

    public function insert(ProductVariant $product): void;

    //public function delete(ProductVariant $product): void;
}

