<?php 

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;

final class MercadoPagoProvider {
    public function __construct() {
        SDK::setAccessToken($_ENV["MP_ACCESS_TOKEN"]);
    }

    public function generatePreference(array $items): string {
        $preference = new Preference();

        foreach ($items as $itemData) {
            $item = new Item();
            $item->title = $itemData["productId"]; // Podés usar el nombre real si lo buscás antes
            $item->quantity = $itemData["quantity"];
            $item->unit_price = $this->getPrice($itemData["productId"]); // Implementar lógica de precios
            $preference->items[] = $item;
        }

        $preference->save();
        return $preference->init_point;
    }

    private function getPrice(string $productId): float {
        // Buscar precio desde repositorio de productos
        return 100.0; // Placeholder
    }
}
