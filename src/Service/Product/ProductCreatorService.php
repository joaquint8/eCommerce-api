<?php 

namespace Src\Service\Product;

use DateTime;
use Src\Entity\Product\Product;
use Src\Infrastructure\Repository\Product\ProductRepository;
final readonly class ProductCreatorService {
    private ProductRepository $repository;

    public function __construct() {
        $this->repository = new ProductRepository();
    }

    public function create(string $name, string $description, float $price, int $categoryId): void
    {
    //Utiliza el Create de Entity, luego Insert de Repository
        $Product = Product::create($name, $description, $price, $categoryId);
        $this->repository->insert($Product);
    }
}