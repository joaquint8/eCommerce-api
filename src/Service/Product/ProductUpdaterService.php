<?php 

namespace Src\Service\Product;

use DateTime;
use Src\Entity\Product\ProductState;
use Src\Infrastructure\Repository\Product\ProductRepository;

final readonly class ProductUpdaterService {
    private ProductRepository $repository;
    private ProductFinderService $finder;

    public function __construct() {
        $this->repository = new ProductRepository();
        $this->finder = new ProductFinderService();
    }

    public function update(string $name, string $description, float $price, int $stock, ProductState $state, int $categoryId, string $imageUrl, int $id): void
    {
        $Product = $this->finder->find($id);

        $Product->modify($name, $description, $price, $stock, $state, $categoryId, $imageUrl);
        //Modify es un setter para todos 
        $this->repository->update($Product);
    }
}