<?php 

namespace Src\Service\Product;

use Src\Entity\Product\Product;
use Src\Infrastructure\Repository\Product\ProductRepository;
use Src\Entity\Product\Exception\ProductNotFoundException;

final readonly class ProductFinderService {

    private ProductRepository $repository; 

    public function __construct() {
        $this->repository = new ProductRepository();
    }

    public function find(int $id): Product {  

        $product = $this->repository->find($id);

        if ($product === null) {
            throw new ProductNotFoundException($id);
        }

        $images = $this->productImagesSearcherByProductService->search($product->id());
        $product->setImages($images);

        $variants = $this->productVariantsSearcherByProductService->search($product->id());
        $product->setVariants($variants);

        return $product;
    }
}