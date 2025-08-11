<?php

use Src\Utils\ControllerUtils;
use Src\Service\Product\ProductCreatorService;

final readonly class ProductPostController {
    private ProductCreatorService $service;

    public function __construct() {
        $this->service = new ProductCreatorService();
    }

    public function start(): void
    { 
        $name = ControllerUtils::getPost("name");
        $description = ControllerUtils::getPost("description");
        $price = ControllerUtils::getPost("price");
        $categoryId = ControllerUtils::getPost("categoryId");
       
        $this->service->create($name, $description, $price,$categoryId);
    }
}