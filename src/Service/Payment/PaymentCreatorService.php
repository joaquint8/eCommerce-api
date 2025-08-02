<?php 

use Src\Infrastructure\Provider\Payment\MercadoPagoProvider;

final readonly class PaymentCreatorService {
    private MercadoPagoProvider $provider;

    public function __construct() {
        $this->provider = new MercadoPagoProvider();
    }

    public function create(string $userId, array $items): string {
        // Validar productos, stock, precios (puede usar repositorios)
        // Armar preferencia
        return $this->provider->generatePreference($items);
    }
}
