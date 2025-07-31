<?php 

namespace Src\Service\Product;

use Src\Infrastructure\Repository\Product\ProductRepository;

final readonly class ProductPhysicalDeleterService {
    private ProductRepository $repository;
    private ProductDeletedFinderService $finder;

    public function __construct() {
        $this->repository = new ProductRepository();
        $this->finder = new ProductDeletedFinderService();
    }

    public function delete(int $id): void
    {
        $Product = $this->finder->find($id);

        $this->repository->delete($Product);
    } 
}