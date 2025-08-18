<?php 

namespace Src\Infrastructure\Repository\Product;

use Src\Entity\Product\Product;

interface ProductRepositoryInterface {
    public function find(int $id): ?Product;

    public function update(Product $product): void;

    /** @return Product[] */
    public function search(): array;

    //public function insert(Product $product): int;

}

