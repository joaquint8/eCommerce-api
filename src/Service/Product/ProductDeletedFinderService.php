<?php 

namespace Src\Service\Product;

use Src\Entity\Product\Product;
use Src\Infrastructure\Repository\Product\ProductRepository;
use Src\Entity\Product\Exception\ProductNotFoundException;

final readonly class ProductDeletedFinderService {

    private ProductRepository $repository;

    public function __construct() {
        $this->repository = new ProductRepository();
    }

    public function find(int $id): Product 
    {   
        $Product = $this->repository->findDeleted($id);

        if ($Product === null) {
            throw new ProductNotFoundException($id, "No existe ningun producto eliminado con ese Id.");
        }

        return $Product;
    }
}