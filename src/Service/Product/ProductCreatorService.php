<?php 

namespace Src\Service\Product;

use DateTime;
use Src\Entity\Product\Product;
use Src\Infrastructure\Repository\Product\ProductRepository;
use Src\Entity\Product\ProductState;

final readonly class ProductCreatorService {
    private ProductRepository $repository;

    public function __construct() {
        $this->repository = new ProductRepository();
    }

    public function create(string $name, string $description, float $price, int $stock, ProductState $state, DateTime $creationDate, int $categoryId, string $imageUrl): void
    {
    //Utiliza el Create de Entity, luego Insert de Repository
        $Product = Product::create($name, $description, $price, $stock, $state, $creationDate = new DateTime(), $categoryId, $imageUrl);
        $this->repository->insert($Product);
    }
}