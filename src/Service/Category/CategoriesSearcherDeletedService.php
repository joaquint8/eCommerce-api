<?php

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\Category;

use Src\Entity\Category\Category;
use Src\Infrastructure\Repository\Category\CategoryRepository;

final readonly class CategoriesSearcherDeletedService {
    private CategoryRepository $repository;

    public function __construct() {
        $this->repository = new CategoryRepository();
    }

    /** @return Category[] **/
    public function search(): array
    {
        return $this->repository->searchDeleted();
    }
}