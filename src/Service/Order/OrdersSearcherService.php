<?php

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\Order;

use Src\Entity\Order\Order;
use Src\Infrastructure\Repository\Order\OrderRepository;

final readonly class OrdersSearcherService {
    private OrderRepository $repository;

    public function __construct() {
        $this->repository = new OrderRepository();
    }

    /** @return Order[] **/
    public function search(): array
    {
        return $this->repository->search();
    }
}