<?php

namespace Src\Service\Order;

use Src\Entity\Order\Order;
use Src\Infrastructure\Repository\Order\OrderRepository;

final readonly class OrderFinderService {
    private OrderRepository $repository;

    public function __construct() {
        $this->repository = new OrderRepository();
    }

    public function find(int $id): Order {
        return $this->repository->find($id);
    }
}
