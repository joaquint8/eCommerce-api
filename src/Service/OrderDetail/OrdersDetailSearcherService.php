<?php

namespace Src\Service\OrderDetail;
/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */

use Src\Infrastructure\Repository\OrderDetail\OrderDetailRepository;
use Src\Entity\OrderDetail\OrderDetail;

final readonly class OrdersDetailSearcherService {
    private OrderDetailRepository $repository; 

    public function __construct() {
        $this->repository = new OrderDetailRepository();
    }

    /** @return OrderDetail[] **/ 
    public function search(): array
    {
        return $this->repository->search();
    }
}