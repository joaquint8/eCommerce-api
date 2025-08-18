<?php

// Importamos el servicio que se encarga de crear la orden y generar la preferencia de pago
use Src\Service\Payment\PaymentCreatorService;

// Este controlador maneja la creación de pagos.
// Recibe los datos del cliente desde el frontend, valida los campos,
// genera una orden en la base de datos y crea una preferencia de pago en Mercado Pago.

final readonly class PaymentCreateController {
    // Instancia del servicio que encapsula la lógica de negocio para crear pagos
    private PaymentCreatorService $service;

    public function __construct() {
        // Inicializamos el servicio en el constructor
        $this->service = new PaymentCreatorService();
    }

    // Método principal que se ejecuta cuando se llama al endpoint /payment/create
    public function start(): void {
        // Leemos el cuerpo de la petición HTTP y lo convertimos de JSON a array
        $body = json_decode(file_get_contents("php://input"), true);

        // Validamos que todos los campos requeridos estén presentes en el cuerpo de la solicitud
        $requiredKeys = ["userId", "items", "payer", "shippingAddress", "total"];
        foreach ($requiredKeys as $key) {
            if (!isset($body[$key])) {
                // Si falta algún campo, devolvemos un error 400 con un mensaje específico
                http_response_code(400);
                echo json_encode(["error" => "Falta el campo requerido: $key"]);
                return;
            }
        }

        // Generamos un identificador único para la orden (external_reference)
        // Este valor se usará para vincular la orden con el pago en Mercado Pago
        $externalReference = uniqid("order_", true);

        // Llamamos al servicio para crear la orden y generar la preferencia de pago
        $result = $this->service->createPayment(
            $body["items"],             // Lista de productos que el usuario quiere comprar
            $body["payer"],             // Información del pagador (nombre, email, etc.)
            $body["shippingAddress"],   // Dirección de envío
            $externalReference,         // Referencia única para rastrear la orden
            $body["total"],             // Monto total de la compra
            $body["userId"]             // ID del usuario que está realizando el pago
        );

        // Devolvemos al frontend el link de pago (init_point) y el ID de la orden
        echo json_encode([
            "init_point" => $result["preference"]?->init_point ?? '', // URL para redirigir al usuario a Mercado Pago
            "order_id" => $result["order_id"]                          // ID interno de la orden en la base de datos
        ]);
    }
}
