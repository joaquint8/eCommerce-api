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
            "updated_at" => $product->updated_at()->format('Y-m-d H:i:s'),
            "variants" => array_map(function ($v) { // a cada elemento del array y devolver un nuevo array con los resultados.transformar cada objeto variant a un array asociativo
                return [
                    "id" => $v->id(),
                    "color" => $v->color(),
                    "size" => $v->size(),
                    "stock" => $v->stock(),
                    "state" => $v->state(),
                ];
            }, $product->getVariants())
        ]);
    }
}