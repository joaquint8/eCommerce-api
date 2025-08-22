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
        $product = $this->repository->findDeleted($id);

        if (!$product || !$product->isDeleted()) { //Si no encuentro la categoria (variable Product no existe), o si la categoria no está marcada como borrada (deleted = 0) lanzo una exepcion
            throw new ProductNotFoundException($id,"La categoria no esta en papelera para restaurar.");
        }

        $this->repository->restore($product);

        $variantRepo = new \Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository();
        $variantRepo->restoreByProductId($product->id());

        $imageRepo = new \Src\Infrastructure\Repository\ProductImages\ProductImagesRepository();
        $imageRepo->restoreByProductId($product->id());
    }
}