<?php 

use Src\Service\Payment\PaymentCreatorService;

final readonly class PaymentCreateController {
    private PaymentCreatorService $service;

    public function __construct() {
        $this->service = new PaymentCreatorService();
    }

    public function start(): void {
        $body = json_decode(file_get_contents("php://input"), true);
        $initPoint = $this->service->create($body["userId"], $body["items"]);

        echo json_encode(["init_point" => $initPoint]);
    }
}
