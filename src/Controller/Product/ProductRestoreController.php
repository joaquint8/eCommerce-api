<?php

use Src\Service\Product\ProductRestoreService;

final readonly class ProductRestoreController {
    private ProductRestoreService $service;

    public function __construct() {
        $this->service = new ProductRestoreService();
    }

    public function start(int $id): void
    {
        $this->service->restore($id);
    }
}