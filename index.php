<?php

    // Cabeceras CORS para todas las respuestas
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    // Manejo de preflight (OPTIONS)
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit(0);
    }

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    // Cargamos configuración de composer
    require_once __DIR__ . '/vendor/autoload.php';

    // Inicializamos el routeador
    require_once __DIR__ . '/app/Router/Routes.php';

    // Inicializamos el autoloader
    require_once __DIR__ . '/app/Autoloader/Autoloader.php';

    // Cargamos variables de entorno con Dotenv
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Autoload personalizado
    spl_autoload_register(function ($class): void {
        Autoloader::register($class, [
            "src/Service",
            "src/Entity",
            "src/Infrastructure",
            "src/Utils",
            "src/Middleware"
        ]);
    });

    // Iniciamos el router
    $router = startRouter();

    // Obtenemos la URL limpia
    $url = explode("?", $_SERVER["REQUEST_URI"])[0];

    try {
        // Debug: mostrar headers enviados
        header('Content-Type: application/json');
        header('X-Debug-CORS: headers enviados correctamente');

        // Resolución de rutas
        $router->resolve($url, $_SERVER['REQUEST_METHOD']);
    } catch (Exception $e) {
        // Error 404 si la ruta no existe
        header("HTTP/1.0 404 Not Found");
        echo json_encode([
            "status" => 404,
            "message"=> $e->getMessage()
        ]);
    }
