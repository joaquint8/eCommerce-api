<?php

namespace Src\Service\Category;

use Src\Infrastructure\Repository\Category\CategoryRepository;
use Src\Entity\Category\Exception\CategoryNotFoundException;

final readonly class CategoryRestoreService {
    private CategoryRepository $repository;

    public function __construct() {
        $this->repository = new CategoryRepository();
    }

    public function restore(int $id): void
    {
        $category = $this->repository->findDeleted($id);

        if (!$category || !$category->isDeleted()) { //Si no encuentro la categoria (variable category no existe), o si la categoria no está marcada como borrada (deleted = 0) lanzo una exepcion
            throw new CategoryNotFoundException($id,"La categoria no esta en papelera para restaurar.");
        }

        $this->repository->restore($category);
    }
}
