<?php 

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\Product;

use Src\Entity\Product\Product;
use Src\Entity\Product\ProductImages;
use Src\Entity\Product\ProductVariant;
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
        $products = $this->repository->search(); // 1 query para traer todos los productos
        $variants = $this->productVariantRepository->search(); //1 query para traer todas las variantes
        $images = $this->productImagesRepository->search();// 1 query para traer todas las imágenes


        $images = $this->filterImages($images);
        $variants = $this->filterVariants($variants);

        foreach ($products as $product) {
            $product->setVariants($variants[$product->id()] ?? []);
            $product->setImages($images[$product->id()] ?? []);
        }

        return $products;
    }



   //capaz que sea mejor hacer una funcion general. porque filterImages y filterVariants son iguales
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
            function(array $items, ProductImages $image) {
                $items[$image->productId()][] = $image;
                return $items;
            },
            []
        );
    }

    private function filterVariants(array $variants): array
    {
       
        return array_reduce(
            $variants,
            function(array $items, ProductVariant $variant) {
                $items[$variant->productId()][] = $variant;
                return $items;
            },
            []
        );
    }
}