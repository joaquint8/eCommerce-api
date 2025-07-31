<?php 

use Src\Service\Product\ProductsSearcherService;
final readonly class ProductsGetController {
    private ProductsSearcherService $service; 

    public function __construct() {
        $this->service = new ProductsSearcherService(); 
    }

    public function start(): void{
        $Product = $this->service->search();

        echo json_encode($this->toResponse($Product));
    }

    private function toResponse(array $Product): array{
        $responses = [];
        
        foreach($Product as $product) {
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