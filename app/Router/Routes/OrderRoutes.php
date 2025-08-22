<?php

final readonly class OrderRoutes {
    public static function getRoutes(): array {
        return [
            [
                "name" => "order_get",
                "url" => "/order",
                "controller" => "Order/OrderGetController.php",
                "method" => "GET",
                "parameters" => [
                    [
                        "name" => "id",
                        "type" => "int"
                    ]
                ]
            ],
            [
                "name" => "orders_get",
                "url" => "/order",
                "controller" => "Order/OrdersGetController.php",
                "method" => "GET"
            ]
            
        ];
    }
}
