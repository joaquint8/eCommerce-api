<?php

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\Product;

use Src\Entity\Product\Product;
use Src\Infrastructure\Repository\Product\ProductRepository;

final readonly class ProductsSearcherDeletedService {
    private ProductRepository $repository;

    public function __construct() {
        $this->repository = new ProductRepository();
    }

    /** @return Product[] **/
    public function search(): array
    {
        return $this->repository->searchDeleted();
    }
}