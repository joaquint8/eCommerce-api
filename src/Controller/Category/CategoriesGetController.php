<?php 

use Src\Service\Category\CategoriesSearcherService;
final readonly class CategoriesGetController {
    private CategoriesSearcherService $service; 

    public function __construct() {
        $this->service = new CategoriesSearcherService(); 
    }

    public function start(): void{
        $categories = $this->service->search();

        echo json_encode($this->toResponse($categories));
    }

    private function toResponse(array $categories): array{
        $responses = [];
        
        foreach($categories as $category) {
            $responses[] = [
                "id" => $category->id(),
                "name" => $category->name(),
                "created_at" => $category->created_at()->format('Y-m-d H:i:s'),
                "updated_at" => $category->updated_at()->format('Y-m-d H:i:s'),
                "deleted" => $category->isDeleted()
            ];
        }

        return $responses;
    }
}