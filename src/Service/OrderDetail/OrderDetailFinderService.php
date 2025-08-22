<?php

namespace Src\Service\OrderDetail;

use Src\Infrastructure\Repository\OrderDetail\OrderDetailRepository;
use Src\Entity\OrderDetail\OrderDetail;

final class OrderDetailFinderService {
    private OrderDetailRepository $repository;

    public function __construct() {
        $this->repository = new OrderDetailRepository();
    }

    public function find(int $id): ?OrderDetail {
        return $this->repository->find($id);
    }
}
