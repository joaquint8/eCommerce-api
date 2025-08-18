<?php

final readonly class PaymentRoutes {
    public static function getRoutes(): array {
        return [
            [
                "name" => "payment_create",
                "url" => "/payment/create",
                "controller" => "Payment/PaymentCreateController.php",
                "method" => "POST"
            ],
            [
                "name" => "payment_webhook",
                "url" => "/payment/webhook",
                "controller" => "Payment/PaymentWebhookController.php",
                "method" => "POST"
            ]
        ];
    }
}
