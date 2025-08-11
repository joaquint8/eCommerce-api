<?php 

namespace Src\Infrastructure\Repository\ProductVariant;

use Src\Entity\Product\ProductVariant;

interface ProductVariantRepositoryInterface {
    //public function find(int $id): ?ProductVariant;

    /** @return ProductVariant[] */
    //public function search(): array;

    public function insert(ProductVariant $product): void;

    //public function delete(ProductVariant $product): void;
}

