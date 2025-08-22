<?php

use Src\Service\Category\CategoryPhysicalDeleterService;

final readonly class CategoryPhysicalDeleteController {
    private CategoryPhysicalDeleterService $service;

    public function __construct() {
        $this->service = new CategoryPhysicalDeleterService();
    }

    public function start(int $id): void
    {
        $this->service->delete($id);
    }
}