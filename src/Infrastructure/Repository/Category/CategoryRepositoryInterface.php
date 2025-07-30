<?php 

namespace Src\Infrastructure\Repository\Category;

use Src\Entity\Category\Category;

interface CategoryRepositoryInterface {
    
    public function insert(Category $category): void;

    public function delete(Category $category): void;

    public function update(Category $category): void;
    
    /** @return Category[] */
    public function search(): array;

    /** @return Category[] */
    public function searchDeleted(): array;
    
    public function find(int $id): ?Category;

    public function findDeleted(int $id): ?Category;
    
    public function restore(Category $author): void;
}