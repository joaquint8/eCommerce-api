<?php 

namespace Src\Service\Payment;

use Src\Infrastructure\Provider\Payment\MercadoPagoProvider;

final readonly class PaymentCreatorService {
    private MercadoPagoProvider $provider;

    public function __construct() {
        $this->provider = new MercadoPagoProvider();
    }

    public function create(string $userId, array $items): string {
        $payer = [
            "name" => "Usuario",
            "surname" => "Anonimo",
            "email" => "usuario@example.com"
        ];

        $preference = $this->provider->generatePreference($items, $payer);
        return $preference?->init_point ?? '';
    }
}
