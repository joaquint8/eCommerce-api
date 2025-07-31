<?php 

namespace Src\Service\Category;

use Src\Entity\Category\Category;
use Src\Infrastructure\Repository\Category\CategoryRepository;
use Src\Entity\Category\Exception\CategoryNotFoundException;

final readonly class CategoryDeletedFinderService {

    private CategoryRepository $repository;

    public function __construct() {
        $this->repository = new CategoryRepository();
    }

    public function find(int $id): Category 
    {   
        $Category = $this->repository->findDeleted($id);

        if ($Category === null) {
            throw new CategoryNotFoundException($id, "No existe ningun autor eliminado con ese Id.");
        }

        return $Category;
    }
}