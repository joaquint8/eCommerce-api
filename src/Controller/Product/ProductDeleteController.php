<?php 

use Src\Service\Product\ProductDeleterService;

final readonly class ProductDeleteController {
    private ProductDeleterService $service;

    public function __construct() {
        $this->service = new ProductDeleterService();
    }

    public function start(int $id): void
    {
        $this->service->delete($id);
    }
}