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
        $category = $this->repository->findDeleted($id);

        if ($category === null) {
            throw new CategoryNotFoundException($id, "No existe ninguna categoria eliminada con ese Id.");
        }

        return $category;
    }
}