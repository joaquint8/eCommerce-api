<?php 

use Src\Service\Product\ProductFinderService;

final readonly class ProductGetController {

    private ProductFinderService $service; 

    public function __construct() {
        $this->service = new ProductFinderService(); 
    }

    public function start(int $id): void{

        $product = $this->service->find($id); 

        echo json_encode([ 
            "id" => $product->id(),
            "name" => $product->name(),
            "description" => $product->description(),
            "price" => $product->price(),
            "categoryId" => $product->categoryId(),
            "deleted" => $product->isDeleted(),
            "created_at" => $product->created_at()->format('Y-m-d H:i:s'),
            "updated_at" => $product->updated_at()->format('Y-m-d H:i:s')
        ]);
    }
}