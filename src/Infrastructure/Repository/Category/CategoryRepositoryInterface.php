<?php 

namespace Src\Infrastructure\Repository\Category;

use Src\Entity\Category\Category;

interface CategoryRepositoryInterface {
    
    public function insert(Category $category): void;

    public function delete(Category $category): void;

    public function update(Category $category): void;
    
    /** @return Category[] */
    public function search(): array;

    public function find(int $id): ?Category;

   
}