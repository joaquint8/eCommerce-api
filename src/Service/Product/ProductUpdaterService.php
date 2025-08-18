<?php 

namespace Src\Service\Product;

use DateTime;
use Src\Infrastructure\Repository\Product\ProductRepository;

final readonly class ProductUpdaterService {
    private ProductRepository $repository;
    private ProductFinderService $finder;

    public function __construct() {
        $this->repository = new ProductRepository();
        $this->finder = new ProductFinderService();
    }

    public function update(string $name, string $description, int $id,float $price,int $categoryId): void
    {
        $Product = $this->finder->find($id);

        $Product->modify($name, $description,$price, $categoryId);
        //Modify es un setter para todos 
        $this->repository->update($Product);
    }
}