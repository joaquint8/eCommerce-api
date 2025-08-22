<?php

final readonly class OrderDetailRoutes {
    public static function getRoutes(): array {
        return [
            [
                "name" => "orderDetail_get",
                "url" => "/orderDetail",
                "controller" => "OrderDetail/OrderDetailGetController.php",
                "method" => "GET",
                "parameters" => [
                    [
                        "name" => "id",
                        "type" => "int"
                    ]
                ]
            ],
            [
                "name" => "ordersDetail_get",
                "url" => "/orderDetail",
                "controller" => "OrderDetail/OrdersDetailGetController.php",
                "method" => "GET"
            ]
        ];
    }
}
