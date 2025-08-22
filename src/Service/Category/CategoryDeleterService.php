<?php

namespace Src\Service\Category;

use Src\Infrastructure\Repository\Category\CategoryRepository;

final readonly class CategoryDeleterService {
    private CategoryRepository $repository;
    private CategoryFinderService $finder;

    public function __construct() {
        $this->repository = new CategoryRepository();
        $this->finder = new CategoryFinderService();
    }

    public function delete(int $id): void
    {
        $Category = $this->finder->find($id);

        $Category->delete();
        //Utiliza el Borrado Logico de la Entidad
        $this->repository->update($Category);
    }
}