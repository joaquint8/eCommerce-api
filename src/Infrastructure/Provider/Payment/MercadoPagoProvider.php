<?php
namespace src\Infrastructure\Provider\Payment;

// SDK oficial de Mercado Pago
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;

// Repositorio para obtener información de productos desde la base de datos
use Src\Infrastructure\Repository\Product\ProductRepository;

// Proveedor que encapsula la lógica para interactuar con Mercado Pago
class MercadoPagoProvider {

    public function __construct() {
        // Configuramos el token de acceso desde las variables de entorno
        MercadoPagoConfig::setAccessToken($_ENV["MP_ACCESS_TOKEN"]);

        // Indicamos que estamos en entorno local (útil para pruebas)
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    // Crea el array de configuración para generar una preferencia de pago
    private function createPreferenceRequest(array $rawItems, array $payer, string $orderId, string $userId, string $externalReference): array {
        $productRepository = new ProductRepository(); // Repositorio para obtener el nombre del producto

        $items = [];

        foreach ($rawItems as $rawItem) {
            $productId = (int) $rawItem['product_id'];

            // Buscamos el producto en la base de datos para obtener su nombre
            $product = $productRepository->find($productId);

            if (!$product) {
                // Si no se encuentra el producto, lanzamos una excepción
                throw new \RuntimeException("Producto con ID $productId no encontrado");
            }

            // Armamos el ítem con los datos requeridos por Mercado Pago
            $items[] = [
                'title' => $product->name(),               // Nombre del producto
                'quantity' => (int) $rawItem['quantity'],  // Cantidad
                'unit_price' => (float) $rawItem['price'], // Precio unitario
                'currency_id' => 'ARS'                     // Moneda (pesos argentinos)
            ];
        }

        // Retornamos el array completo con todos los datos de la preferencia
        return [
            "items" => $items,
            "payer" => $payer, // Datos del pagador (email, nombre, etc.)
            "payment_methods" => [
                "excluded_payment_methods" => [], // No excluimos ningún método
                "installments" => 12,             // Máximo de cuotas permitidas
                "default_installments" => 1       // Cuotas por defecto
            ],
            "back_urls" => [                      // URLs de redirección según el resultado del pago
                'success' => 'https://hola.com',
                'failure' => 'https://fallo.com',
            ],
            "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING", // Texto que aparece en el resumen de tarjeta
            "external_reference" => $externalReference, // Referencia única para rastrear la orden
            "notification_url" => "https://44b3783807aa.ngrok-free.app/payment/webhook", // Webhook para recibir notificaciones
            "metadata" => [                       // Datos adicionales para trazabilidad
                "user_id" => $userId,
                "order_id" => $orderId
            ],
            "expires" => false,                   // La preferencia no expira automáticamente
            "auto_return" => 'approved',          // Redirigir automáticamente si el pago fue aprobado
        ];
    }

    // 🔹 Genera la preferencia de pago en Mercado Pago
    public function generatePreference(array $items, array $payer, string $orderId, string $userId, string $externalReference) {
        // Creamos el array de configuración
        $request = $this->createPreferenceRequest($items, $payer, $orderId, $userId, $externalReference);

        // Cliente del SDK para crear preferencias
        $client = new PreferenceClient();

        // Opciones de la solicitud, incluyendo una clave de idempotencia
        $requestOptions = new RequestOptions();
        $requestOptions->setCustomHeaders([
            "X-Idempotency-Key: " . uniqid("pref_", true) // Evita duplicados si se reintenta la solicitud
        ]);

        try {
            // Enviamos la solicitud a Mercado Pago
            return $client->create($request, $requestOptions);
        } catch (MPApiException $error) {
            // Si ocurre un error, lo registramos en los logs
            $apiResponse = $error->getApiResponse();
            $content = $apiResponse ? $apiResponse->getContent() : 'Sin contenido en la respuesta de error';
            error_log("MP Error para OrderID $orderId y UserID $userId: " . $content);
            return null;
        }
    }

    // Consulta un pago por su ID (usado en el webhook para validar estado)
    public function getPaymentById(string $paymentId) {
        $client = new PaymentClient();

        try {
            return $client->get($paymentId);
        } catch (MPApiException $error) {
            // Si ocurre un error, devolvemos null
            return null;
        }
    }
}