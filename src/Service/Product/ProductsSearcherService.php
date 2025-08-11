<?php 

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\Product;

use Src\Entity\Product\Product;
use Src\Infrastructure\Repository\Product\ProductRepository;
use Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository;
use Src\Infrastructure\Repository\ProductImages\ProductImagesRepository;

final readonly class ProductsSearcherService {
    private ProductRepository $repository; 
    private ProductVariantRepository $productVariantRepository; 
    private ProductImagesRepository $productImagesRepository; 

    public function __construct() {
        $this->repository = new ProductRepository();
        $this->productVariantRepository = new ProductVariantRepository();
        $this->productImagesRepository = new ProductImagesRepository();
    }

    /** @return Product[] **/ 
    public function search(): array
    {
        $products = $this->repository->search();

    foreach ($products as $product) {
        $variants = $this->productVariantRepository->findByProductId($product->id()); 
        $images = $this->productImagesRepository->findByProductId($product->id());

        $product->setVariants($variants);
        $product->setImages($images);
    }

    return $products;
    }
}