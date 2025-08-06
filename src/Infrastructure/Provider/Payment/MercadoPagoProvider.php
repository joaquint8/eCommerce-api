<?php

namespace src\Infrastructure\Provider\Payment;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoProvider {
    public function __construct() {
        MercadoPagoConfig::setAccessToken($_ENV["MP_ACCESS_TOKEN"]);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        echo "SDK cargado correctamente";
    }

    private function getPrice(string $productId): float {
        // Retrieve price from product repository
        return 100.0; // Placeholder
    }

    private function createPreferenceRequest($items, $payer): array
    {
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,
            "default_installments" => 1
        ];

        $backUrls = array(
            'success' => 'https://hola.com',
            'failure' => 'https://fallo.com',
        );

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
            "external_reference" => "1234567890", //MUY IMPORTANTE, DIFERENCIA LA VENTA (ID DE LA VENTA DE LA BD)
            "expires" => false,
            "auto_return" => 'approved',
        ];

        return $request;
    }

    public function generatePreference(array $items, array $payer)
    {
        $request = $this->createPreferenceRequest($items, $payer);
        $client = new PreferenceClient();

        try {
            $preference = $client->create($request);
            return $preference;
        } catch (MPApiException $error) {
            return null;
        }
    }

}
