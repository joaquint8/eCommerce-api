<?php

namespace Src\Service\Payment;

// SDK de Mercado Pago y clases auxiliares
use Exception;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Net\MPSearchRequest;

// Repositorio para guardar órdenes en la base de datos
use Src\Infrastructure\Repository\Order\OrderRepository;

// Entidad que representa una orden
use Src\Entity\Order\Order;

// Utilidades para fechas y conexión
use PDO;
use DateTime;

// Servicio que maneja la lógica del webhook para registrar pagos aprobados
final class PaymentWebhookService {
    private OrderRepository $repository;     // Repositorio para persistencia de órdenes
    private PaymentClient $paymentClient;    // Cliente para consultar pagos en Mercado Pago

    public function __construct(OrderRepository $repository) {
        // Configuramos el SDK con el token de acceso y entorno local
        MercadoPagoConfig::setAccessToken($_ENV["MP_ACCESS_TOKEN"]);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

        $this->repository = $repository;
        $this->paymentClient = new PaymentClient();
    }

    // Maneja un pago recibido desde el webhook
    public function handlePayment(string $paymentId): void {
        try {
            // Consultamos el pago por su ID
            $payment = $this->paymentClient->get(intval($paymentId));

            // También podrías listar pagos con búsqueda:
            // $payments = $this->paymentClient->search(new MPSearchRequest(0, 10));
            // var_dump($payments->results); exit();

            // Validamos que el pago exista y esté aprobado
            if (!$payment || $payment->status !== "approved") {
                error_log("Pago no aprobado o no encontrado: $paymentId");
                return;
            }

            // Creamos una orden genérica a partir de los datos del pago
            $order = new Order(
                (int) $payment->payer->id,                          // ID del pagador
                externalReference: $payment->external_reference ?? 'sin_ref', // Referencia externa
                total: $payment->transaction_amount,                // Monto total
                shippingAddress: 'Dirección genérica',              // Dirección ficticia (puede mejorarse)
                status: 'pending',                                  // Estado inicial
                createdAt: new DateTime(),                          // Fecha actual
                items: $payment->additional_info['items'] ?? []     // Ítems del pago (puede requerir transformación)
            );

            // Registramos la orden en la base de datos
            $this->repository->create($order);
            error_log("Orden registrada para el pago $paymentId");
        } catch (MPApiException $e) {
            // Logueamos el error y lo lanzamos como excepción
            error_log("Error al obtener el pago: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
