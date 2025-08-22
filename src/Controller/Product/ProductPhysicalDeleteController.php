<?php

use Src\Service\Product\ProductPhysicalDeleterService;

final readonly class ProductPhysicalDeleteController {
    private ProductPhysicalDeleterService $service;

    public function __construct() {
        $this->service = new ProductPhysicalDeleterService();
    }

    public function start(int $id): void
    {
        $this->service->delete($id);
    }
}