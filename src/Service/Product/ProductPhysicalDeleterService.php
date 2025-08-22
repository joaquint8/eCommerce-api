<?php

namespace Src\Service\Product;

use Src\Infrastructure\Repository\Product\ProductRepository;

final readonly class ProductPhysicalDeleterService {
    private ProductRepository $repository;
    private ProductDeletedFinderService $finder;

    public function __construct() {
        $this->repository = new ProductRepository();
        $this->finder = new ProductDeletedFinderService();
    }

    public function delete(int $id): void
    {
        $product = $this->finder->find($id);

        $this->repository->physicalDelete($product);
    
        $variantRepo = new \Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository();
        $variantRepo->physicalDeleteByProductId($product->id());

        $imageRepo = new \Src\Infrastructure\Repository\ProductImages\ProductImagesRepository();
        $imageRepo->physicalDeleteByProductId($product->id());

        $this->repository->physicalDelete($product);
    }
}