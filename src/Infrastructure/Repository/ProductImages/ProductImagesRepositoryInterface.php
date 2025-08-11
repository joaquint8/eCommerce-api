<?php 

namespace Src\Infrastructure\Repository\ProductImages;

use Src\Entity\Product\ProductImages;

interface ProductImagesRepositoryInterface {
    //public function find(int $id): ?ProductImages;

    /** @return ProductImages[] */
    //public function search(): array;

    public function insert(ProductImages $product): void;

    //public function delete(ProductImages $productImages): void;
}

