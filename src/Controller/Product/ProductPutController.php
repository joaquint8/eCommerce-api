<?php 

use Src\Utils\ControllerUtils;
use Src\Service\Product\ProductUpdaterService;
use Src\Entity\Product\ProductState;

final readonly class ProductPutController {
    private ProductUpdaterService $service;

    public function __construct() {
        $this->service = new ProductUpdaterService();
    }

    public function start(int $id): void
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
        $categoryId = ControllerUtils::getPost("categoryId");
        $imageUrl = ControllerUtils::getPost("imageUrl");


        $this->service->update($name, $description, $price, $stock, $state, $categoryId, $imageUrl, $id);
    }
}