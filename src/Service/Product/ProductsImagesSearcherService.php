<?php 

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\Product;

use Src\Entity\Product\ProductImages;
use Src\Infrastructure\Repository\ProductImages\ProductImagesRepository;

final readonly class ProductsImagesSearcherService {
    private ProductImagesRepository $repository; 

    public function __construct() {
        $this->repository = new ProductImagesRepository();
    }

    /** @return ProductImages[] **/     
    public function search(): array
    {
        return $this->repository->search(); 

    }

    

}