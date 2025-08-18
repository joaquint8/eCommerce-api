<?php 

use Src\Utils\ControllerUtils;
use Src\Service\Product\ProductUpdaterService;

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
        $categoryId = ControllerUtils::getPost("categoryId");

        $this->service->update($name, $description, $id,$price, $categoryId);
    }
}