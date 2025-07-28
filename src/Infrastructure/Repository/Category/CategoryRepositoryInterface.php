<?php 

namespace Src\Infrastructure\Repository\Category;

use Src\Entity\Category\Category;

interface CategoryRepositoryInterface {
    public function find(int $id): ?Category;

    /** @return Category[] */
    public function search(): array;

    public function insert(Category $category): void;

    //public function delete(Category $category): void;
}