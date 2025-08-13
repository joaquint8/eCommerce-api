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
        $variants = $this->productVariantRepository->search(); 
        $images = $this->productImagesRepository->search();


        $images = $this->filterImages($images);

        foreach ($products as $product) {
            $product->setVariants($variants);
            $product->setImages($images[$product->id()] ?? []);
        }

        return $products;
    }

    private function filterImages(array $images): array
    {
       /* // El array reduce hace lo mismo que este codigo
        $salida = [];
        foreach ($images as $image) {
            $salida[$image->productId()][] = $image;
        }

        return $salida;
        */
        return array_reduce(
            $images, 
            fn ($items, ProductImage $image) => return $items[$image->productId()][] = $image;
            []
        );
    }
}