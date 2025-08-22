<?php

namespace Src\Service\Product;

use Src\Infrastructure\Repository\Product\ProductRepository;

final readonly class ProductDeleterService {
    private ProductRepository $repository;
    private ProductFinderService $finder;

    public function __construct() {
        $this->repository = new ProductRepository();
        $this->finder = new ProductFinderService();
    }

    public function delete(int $id): void {
        $product = $this->finder->find($id);

        $product->delete(); // Marca el producto como eliminado
        $this->repository->update($product); // Actualiza en la base

        // También eliminamos variantes e imágenes asociadas
        $variantRepo = new \Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository();
        $variantRepo->deleteByProductId($product->id());

        $imageRepo = new \Src\Infrastructure\Repository\ProductImages\ProductImagesRepository();
        $imageRepo->deleteByProductId($product->id());
    }
}