<?php 

final readonly class CategoryRoutes {
  public static function getRoutes(): array {
    return [
        [ 
            "name" => "category_get",
            "url" => "/category",
            "controller" => "Category/CategoryGetController.php",
            "method" => "GET",
            "parameters" => [
                [
                    "name" => "id",
                    "type" => "int"
                ]
            ]
        ],
        [
            "name" => "categories_get",
            "url" => "/category",
            "controller" => "Category/CategoriesGetController.php",
            "method" => "GET"
        ],
        [
            "name" => "category_post",
            "url" => "/category",
            "controller" => "Category/CategoryPostController.php",
            "method" => "POST"
        ],
        [
            "name" => "category_put",
            "url" => "/category",
            "controller" => "Category/CategoryPutController.php",
            "method" => "PUT",
            "parameters" => [
                [
                    "name" => "id",
                    "type" => "int"
                ]
            ]
        ],
        [
            "name" => "category_delete",
            "url" => "/category",
            "controller" => "Category/CategoryDeleteController.php",
            "method" => "DELETE",
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
