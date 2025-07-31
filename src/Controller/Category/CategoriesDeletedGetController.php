<?php 

use Src\Service\Category\CategoriesDeletedSearcherService;

final readonly class CategoriesDeletedGetController {
    private CategoriesDeletedSearcherService $service;

    public function __construct() {
        $this->service = new CategoriesDeletedSearcherService();
    }

    public function start(): void
    {
        $Categories = $this->service->search();
        echo json_encode($this->toResponse($Categories));
    }

    private function toResponse(array $Categories): array 
    {
        $responses = [];
        
        foreach($Categories as $category) {
            $responses[] = [
                "id" => $category->id(),
                "name" => $category->name(),
                "description" => $category->description(),
                "creationDate" => $category->creationDate()->format('Y-m-d H:i:s'),
                "deleted" => $category->isDeleted()
            ];
        }

        return $responses;
    }
}