<?php 

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\Product;

use Src\Entity\Product\ProductVariant;
use Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository;

final readonly class ProductsVariantsSearcherService {
    private ProductVariantRepository $repository; 

    public function __construct() {
        $this->repository = new ProductVariantRepository();
    }

    /** @return ProductVariant[] **/     
    public function search(): array
    {
        return  $this->repository->search(); 
        
    }

   

}
