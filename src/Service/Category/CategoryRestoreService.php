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
        $Category = $this->repository->findDeleted($id);

        if (!$Category || !$Category->isDeleted()) {
            throw new CategoryNotFoundException($id,"La categoria no esta en papelera para restaurar.");
        }

        $this->repository->restore($Category);
    }
}
