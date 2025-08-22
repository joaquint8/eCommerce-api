<?php

use Src\Utils\ControllerUtils;
use Src\Service\Product\ProductUpdaterService;

final readonly class ProductPutController {
    private ProductUpdaterService $service;

    public function __construct() {
        $this->service = new ProductUpdaterService();
    }

    public function start(int $id): void {
        $body = json_decode(file_get_contents("php://input"), true);

        $name = $body["name"];
        $description = $body["description"];
        $price = (float) $body["price"];
        $categoryId = (int) $body["categoryId"];
        $variants = $body["variants"] ?? [];
        $images = $body["images"] ?? [];


        $updatedAt = new DateTime();

        $this->service->update($id, $name, $description, $price, $categoryId, $updatedAt, $variants, $images);
    }
}
