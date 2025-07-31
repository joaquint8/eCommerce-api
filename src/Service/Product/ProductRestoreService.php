<?php

namespace Src\Service\Product;

use Src\Infrastructure\Repository\Product\ProductRepository;
use Src\Entity\Product\Exception\ProductNotFoundException;

final readonly class ProductRestoreService {
    private ProductRepository $repository;

    public function __construct() {
        $this->repository = new ProductRepository();
    }

    public function restore(int $id): void
    {
        $Product = $this->repository->findDeleted($id);

        if (!$Product || !$Product->isDeleted()) {
            throw new ProductNotFoundException($id,"El producto no esta en papelera para restaurar.");
        }

        $this->repository->restore($Product);
    }
}
