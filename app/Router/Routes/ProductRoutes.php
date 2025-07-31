<?php 

final readonly class ProductRoutes {
  public static function getRoutes(): array {
    return [
        [ 
            "name" => "product_get",
            "url" => "/product",
            "controller" => "Product/ProductGetController.php",
            "method" => "GET",
            "parameters" => [
                [
                    "name" => "id",
                    "type" => "int"
                ]
            ]
        ],
        [
            "name" => "products_get",
            "url" => "/product",
            "controller" => "Product/ProductsGetController.php",
            "method" => "GET"
        ],
        [
            "name" => "products_deleted_get",
            "url" => "/product/deleted",
            "controller" => "Product/ProductsDeletedGetController.php",
            "method" => "GET"
        ],
        [
            "name" => "product_post",
            "url" => "/product",
            "controller" => "Product/ProductPostController.php",
            "method" => "POST"
        ],
        [
            "name" => "product_put",
            "url" => "/product",
            "controller" => "Product/ProductPutController.php",
            "method" => "PUT",
            "parameters" => [
                [
                    "name" => "id",
                    "type" => "int"
                ]
            ]
        ],
        [
            "name" => "product_delete",
            "url" => "/product",
            "controller" => "Product/ProductDeleteController.php",
            "method" => "DELETE",
            "parameters" => [
                [
                    "name" => "id",
                    "type" => "int"
                ]
            ]
        ],
        [
            "name" => "product_physical_delete",
            "url" => "/product/physical",
            "controller" => "Product/ProductPhysicalDeleteController.php",
            "method" => "DELETE",
            "parameters" => [
                [
                    "name" => "id",
                    "type" => "int"
                ]
            ]
        ],
        [
            "name" => "product_restore",
            "url" => "/product/restore",
            "controller" => "Product/ProductRestoreController.php",
            "method" => "PATCH",
            "parameters" => [
                [
                    "name" => "id",
                    "type" => "int"
                ]
            ]
        ]
    ];
  }
}
