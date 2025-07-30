<?php 

namespace Src\Service\Category;

use DateTime;
use Src\Infrastructure\Repository\Category\CategoryRepository;

final readonly class CategoryUpdaterService {
    private CategoryRepository $repository;
    private CategoryFinderService $finder;

    public function __construct() {
        $this->repository = new CategoryRepository();
        $this->finder = new CategoryFinderService();
    }

    public function update(string $name, string $description, int $id): void
    {
        $Category = $this->finder->find($id);

        $Category->modify($name, $description);
        //Modify es un setter para todos 
        $this->repository->update($Category);
    }
}