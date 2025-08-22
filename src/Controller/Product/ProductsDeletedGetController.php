<?php

use Src\Service\Product\ProductsSearcherDeletedService;
final readonly class ProductsDeletedGetController {
    private ProductsSearcherDeletedService $service;

    public function __construct() {
        $this->service = new ProductsSearcherDeletedService();
    }

    public function start(): void{
        $products = $this->service->search();

        echo json_encode($this->toResponse($products));
    }

    private function toResponse(array $products): array{
        $responses = [];

        foreach($products as $product) {
            $responses[] = [
                "id" => $product->id(),
                "name" => $product->name(),
                "created_at" => $product->created_at()->format('Y-m-d H:i:s'),
                "updated_at" => $product->updated_at()->format('Y-m-d H:i:s'),
                "deleted" => $product->isDeleted()
            ];
        }

        return $responses;
    }
}