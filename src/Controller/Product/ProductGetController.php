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
            "stock" => $product->stock(),
            "state" => $product->state()->name,
            "imageUrl" => $product->imageUrl(),
            "creationDate" => $product->creationDate()->format('Y-m-d H:i:s'),
            "categoryId" => $product->categoryId(),
            "deleted" => $product->isDeleted()
        ]);
    }
}