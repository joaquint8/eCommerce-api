<?php 

namespace Src\Infrastructure\Repository\Product;

use Src\Entity\Product\Product;

interface ProductRepositoryInterface {
    public function find(int $id): ?Product;

    /** @return Product[] */
    public function search(): array;

    public function insert(Product $product): void;

    //public function delete(Product $product): void;
}

