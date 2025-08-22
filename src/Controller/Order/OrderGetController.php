<?php

use Src\Service\Order\OrderFinderService;

final readonly class OrderGetController {
    private OrderFinderService $service;

    public function __construct() {
        $this->service = new OrderFinderService();
    }

    public function start(int $id): void {
        try {
            $order = $this->service->find($id);

            echo json_encode([
                "id" => $order->id(),
                "external_reference" => $order->externalReference(),
                "total" => $order->total(),
                "shipping_address" => $order->shipping_address(),
                "status" => $order->status()->value,
                "created_at" => $order->created_at()->format("Y-m-d H:i:s"),
                "items" => array_map(fn($item) => $item->toArray(), $order->items())
            ]);
        } catch (\Throwable $e) {
            echo json_encode([
                "error" => "Error al obtener la orden",
                "message" => $e->getMessage()
            ]);
        }
    }
}
