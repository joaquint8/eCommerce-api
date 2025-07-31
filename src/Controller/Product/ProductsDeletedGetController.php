<?php 

use Src\Service\Product\ProductsDeletedSearcherService;

final readonly class ProductsDeletedGetController {
    private ProductsDeletedSearcherService $service;

    public function __construct() {
        $this->service = new ProductsDeletedSearcherService();
    }

    public function start(): void
    {
        $Products = $this->service->search();
        echo json_encode($this->toResponse($Products));
    }

    private function toResponse(array $Products): array 
    {
        $responses = [];
        
        foreach($Products as $product) {
            $responses[] = [
                "id" => $product->id(),
                "name" => $product->name(),
                "description" => $product->description(),
                "price" => $product->price(),
                "stock" => $product->stock(),
                "state" => $product->state()->name,
                "imageUrl" => $product->imageUrl(),
                "creationDate" => $product->creationDate()->format('Y-m-d H:i:s'),
                "categoryId" => $product->categoryId(),
                "deleted" => $product->isDeleted()
            ];
        }

        return $responses;
    }
}