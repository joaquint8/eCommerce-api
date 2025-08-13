<?php 

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\Product;

use Src\Entity\Product\ProductImages;
use Src\Entity\Product\Product;

use Src\Infrastructure\Repository\Product\ProductRepository;
//use Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository;
//use Src\Infrastructure\Repository\ProductImages\ProductImagesRepository;

final readonly class ProductsSearcherService {
    private ProductRepository $repository; 
 
    public function __construct() {
        $this->repository = new ProductRepository();

    }

    /** @return Product[] **/ 
    public function search(): array
    {
        $products = $this->repository->search();

    //lo comento para que no me de error ahora
    foreach ($products as $product) {
        //$variants = $this->productVariantRepository->findByProductId($product->id()); 
        //$images = $this->productImagesRepository->findByProductId($product->id());

        //$product->setVariants($variants); //para los gets del controller
        //$product->setImages($images);
    }

    return $products;
    }
}