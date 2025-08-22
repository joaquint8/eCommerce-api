<?php

use Src\Utils\ControllerUtils;
use Src\Service\Product\ProductCreatorService;

final readonly class ProductPostController {
    private ProductCreatorService $service;

    public function __construct() {
        $this->service = new ProductCreatorService();
    }

    public function start(): void {
        $name = ControllerUtils::getPost("name");
        $description = ControllerUtils::getPost("description");
        $price = (float) ControllerUtils::getPost("price");
        $categoryId = (int) ControllerUtils::getPost("categoryId");
        $variants = ControllerUtils::getPost("variants") ?? [];
        $images = ControllerUtils::getPost("images") ?? [];

        $createdAt = new DateTime();
        $updatedAt = new DateTime();

        $this->service->create($name, $description, $price, $categoryId, $createdAt, $updatedAt, $variants, $images);
    }
}
