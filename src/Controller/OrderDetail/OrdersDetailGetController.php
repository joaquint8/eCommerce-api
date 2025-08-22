<?php

use Src\Service\OrderDetail\OrdersDetailSearcherService;
final readonly class OrdersDetailGetController {
    private OrdersDetailSearcherService $service;

    public function __construct() {
        $this->service = new OrdersDetailSearcherService();
    }

    public function start(): void{
        $ordersDetail = $this->service->search();

        echo json_encode($this->toResponse($ordersDetail));
    }

    private function toResponse(array $ordersDetail): array{
        $responses = [];
        
        foreach($ordersDetail as $orderDetail) {
            $responses[] = [
                "id" => $orderDetail->id(),
                "order_id" => $orderDetail->order_id(),
                "product_id" => $orderDetail->product_id(),
                "quantity"=>$orderDetail->quantity(),
                "unit_price" => $orderDetail->unit_price(),
                "total_price"=>$orderDetail->total_price(),
                "created_at" => $orderDetail->created_at()->format('Y-m-d H:i:s'),
                "updated_at" => $orderDetail->updated_at()->format('Y-m-d H:i:s'),
            ];
        }

        return $responses;
    }
}