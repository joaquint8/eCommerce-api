<?php 

namespace Src\Service\Category;

use Src\Entity\Category\Category;
use Src\Infrastructure\Repository\Category\CategoryRepository;

final readonly class CategoryCreatorService {
    private CategoryRepository $repository;

    public function __construct() {
        $this->repository = new CategoryRepository();
    }

    public function create(string $name, string $description, $creationDate): void
    {
    //Utiliza el Create de Entity, luego Insert de Repository
        $Category = Category::create($name, $description, $creationDate = new \DateTime());
        $this->repository->insert($Category);
    }
}