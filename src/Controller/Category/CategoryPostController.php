<?php

use Src\Utils\ControllerUtils;
use Src\Service\Category\CategoryCreatorService;

final readonly class CategoryPostController {
    private CategoryCreatorService $service;

    public function __construct() {
        $this->service = new CategoryCreatorService();
    }

    public function start(): void
    {
        $name = ControllerUtils::getPost("name");
        $creationDate = new DateTime();
        $updatedDate = new DateTime();

        $this->service->create($name, $creationDate, $updatedDate);
    }
}