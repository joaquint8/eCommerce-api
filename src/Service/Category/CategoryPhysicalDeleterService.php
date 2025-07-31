<?php 

namespace Src\Service\Category;

use Src\Infrastructure\Repository\Category\CategoryRepository;

final readonly class CategoryPhysicalDeleterService {
    private CategoryRepository $repository;
    private CategoryDeletedFinderService $finder;

    public function __construct() {
        $this->repository = new CategoryRepository();
        $this->finder = new CategoryDeletedFinderService();
    }

    public function delete(int $id): void
    {
        $category = $this->finder->find($id);

        $this->repository->delete($category);
    } 
}