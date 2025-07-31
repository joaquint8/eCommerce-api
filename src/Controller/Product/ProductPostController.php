<?php

use Src\Utils\ControllerUtils;
use Src\Service\Product\ProductCreatorService;
use Src\Entity\Product\ProductState;

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
        $stock = ControllerUtils::getPost("stock");
        $stateString = ControllerUtils::getPost("state");
        $state = ProductState::tryFrom($stateString);

        if (!$state) {
            throw new InvalidArgumentException("Estado invalido de producto: $stateString");
        }
        $creationDate = new \DateTime();
        $categoryId = ControllerUtils::getPost("categoryId");
        $imageUrl = ControllerUtils::getPost("imageUrl");

        $this->service->create($name, $description, $price, $stock, $state, $creationDate, $categoryId, $imageUrl);
    }
}