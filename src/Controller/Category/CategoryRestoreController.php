<?php

use Src\Service\Category\CategoryRestoreService;

final readonly class CategoryRestoreController {
    private CategoryRestoreService $service;

    public function __construct() {
        $this->service = new CategoryRestoreService();
    }

    public function start(int $id): void
    {
        $this->service->restore($id);
    }
}
