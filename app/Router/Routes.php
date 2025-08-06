<?php 

include_once "Route.php";
include_once "Router.php";

function startRouter(): Router 
{
    // Inicializamos el array de rutas
    $routes = [];

    include_once "Routes/CategoryRoutes.php";
    $routes = array_merge($routes, CategoryRoutes::getRoutes());

    include_once "Routes/ProductRoutes.php";
    $routes = array_merge($routes, ProductRoutes::getRoutes());

    include_once "Routes/PaymentRoutes.php";
    $routes = array_merge($routes, PaymentRoutes::getRoutes());

    // Como las rutas en este momento son primitivas, tenemos que encapsularlas en un DTO
    $routesClass = [];
    foreach ($routes as $route) {
        // Pasamos de las rutas de tipo array a tipo DTO
        $routesClass[] = Route::fromArray($route);
    }
    
    // Retornamos un nuevo ruteador
    return new Router($routesClass);
}
