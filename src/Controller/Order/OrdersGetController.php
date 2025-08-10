<?php 

use Src\Service\Order\OrdersSearcherService;
final readonly class OrdersGetController {
    private OrdersSearcherService $service; 

    public function __construct() {
        $this->service = new OrdersSearcherService(); 
    }

    public function start(): void{
        $orders = $this->service->search();

        echo json_encode($this->toResponse($orders));
    }

    private function toResponse(array $orders): array{
        $responses = [];
        
        foreach($orders as $order) {
            $responses[] = [
                "id" => $order->id(),
                "total" => $order->total(),
                "shipping_address" => $order->shipping_address(),
                "status"=>$order->status(),
                "created_at" => $order->created_at()->format('Y-m-d H:i:s'),
            ];
        }

        return $responses;
    }
}