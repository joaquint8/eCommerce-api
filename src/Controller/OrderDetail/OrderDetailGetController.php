<?php

use Src\Service\OrderDetail\OrderDetailFinderService;

final readonly class OrderDetailGetController {
    private OrderDetailFinderService $service;

    public function __construct() {
        $this->service = new OrderDetailFinderService();
    }

    public function start(int $id): void {
        try {
            $detail = $this->service->find($id);

            if (!$detail) {
                http_response_code(404);
                echo json_encode(["error" => "OrderDetail no encontrado"]);
                return;
            }

            echo json_encode([
                "id" => $detail->id(),
                "order_id" => $detail->order_id(),
                "product_id" => $detail->product_id(),
                "quantity" => $detail->quantity(),
                "unit_price" => $detail->unit_price(),
                "total_price" => $detail->total_price(),
                "created_at" => $detail->created_at()->format("Y-m-d H:i:s"),
                "updated_at" => $detail->updated_at()->format("Y-m-d H:i:s"),
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Error interno",
                "message" => $e->getMessage()
            ]);
        }
    }
}
