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
        //Tabla Product
        $name = ControllerUtils::getPost("name");
        $description = ControllerUtils::getPost("description");
        $price = ControllerUtils::getPost("price");
        $categoryId = ControllerUtils::getPost("categoryId");

        //Tablas relacionadas (variantes e imagenes)
        $variants = ControllerUtils::getPost("variants") ?? [];
        $images = ControllerUtils::getPost("images") ?? [];
       
        $this->service->create($name, $description, $price,$categoryId,$variants,$images);
    }
}