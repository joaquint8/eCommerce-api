<?php 

use Src\Service\Category\CategoryFinderService;

final readonly class CategoryGetController {

    private CategoryFinderService $service; 

    public function __construct() {
        $this->service = new CategoryFinderService(); 
    }

    public function start(int $id): void{

        $category = $this->service->find($id); 

        echo json_encode([ 
            "id" => $category->id(),
            "name" => $category->name(),
            "created_at" => $category->created_at()->format('Y-m-d H:i:s'),
            "updated_at" => $category->updated_at()->format('Y-m-d H:i:s'),
            "deleted" => $category->isDeleted()
        ]);
    }
}