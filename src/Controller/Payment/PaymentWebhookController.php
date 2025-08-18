<?php

// Importamos el proveedor de Mercado Pago para consultar el estado del pago
use Src\Infrastructure\Provider\Payment\MercadoPagoProvider;

// Importamos el repositorio de órdenes para actualizar su estado
use Src\Infrastructure\Repository\Order\OrderRepository;

// Controlador que maneja los eventos entrantes desde el webhook de Mercado Pago
final readonly class PaymentWebhookController {
    private MercadoPagoProvider $provider;     // Cliente para interactuar con Mercado Pago
    private OrderRepository $repository;       // Repositorio para actualizar la orden

    public function __construct() {
        // Inicializamos las dependencias
        $this->provider = new MercadoPagoProvider();
        $this->repository = new OrderRepository();
    }

    // Método principal que se ejecuta cuando Mercado Pago envía una notificación
    public function start(): void {
        // Leemos el cuerpo de la solicitud HTTP
        $body = file_get_contents("php://input");
        $data = json_decode($body, true); // Convertimos el JSON en array

        // Validamos que el tipo de evento sea "payment"
        if (!isset($data["type"]) || $data["type"] !== "payment") {
            http_response_code(400);
            echo json_encode(["error" => "Tipo de evento no soportado"]);
            return;
        }

        // Extraemos el ID del pago desde la estructura del webhook
        $paymentId = $data["data"]["id"] ?? null;
        if (!$paymentId) {
            http_response_code(400);
            echo json_encode(["error" => "payment_id faltante"]);
            return;
        }

        // Consultamos los detalles del pago usando el SDK de Mercado Pago
        $payment = $this->provider->getPaymentById($paymentId);
        if (!$payment) {
            http_response_code(500);
            echo json_encode(["error" => "No se pudo obtener el pago"]);
            return;
        }

        // Extraemos el estado del pago y la referencia externa (external_reference)
        $status = $payment->status;
        $externalReference = $payment->external_reference ?? null;

        // Log para trazabilidad del webhook recibido
        error_log("Webhook recibido: payment_id={$paymentId}, status={$status}, external_reference={$externalReference}");

        // Validamos que se haya recibido la referencia externa
        if (!$externalReference) {
            error_log("No se recibió external_reference en el pago");
            http_response_code(400);
            return;
        }

        // Actualizamos el estado de la orden en la base de datos
        $updated = $this->repository->updateStatusByReference($externalReference, $status);

        if ($updated) {
            // Log si la orden fue actualizada correctamente
            error_log("Orden actualizada: external_reference={$externalReference}, status={$status}");
        } else {
            // Log si no se encontró la orden correspondiente
            error_log("No se encontró la orden: external_reference={$externalReference}");
        }

        // Respondemos con éxito al webhook
        http_response_code(200);
        echo json_encode([
            "message" => "Webhook procesado correctamente",
            "external_reference" => $externalReference,
            "status" => $status
        ]);
    }
}