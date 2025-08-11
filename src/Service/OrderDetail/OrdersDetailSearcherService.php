<?php 

/*LOGICA DE NEGOCIO.actúa como intermediario entre los controladores y el repositorio de datos, encapsulando la lógica */
namespace Src\Service\OrderDetail;

use Src\Entity\OrderDetail\OrderDetail;
use Src\Infrastructure\Repository\OrderDetail\OrderDetailRepository;

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