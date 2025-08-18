<?php
namespace Src\Entity\Order;

// Entidad que representa un ítem dentro de una orden.
// Contiene el ID del producto, la cantidad comprada y el precio unitario.

final class OrderItem {
    private int $productId;   // ID del producto comprado
    private int $quantity;    // Cantidad de unidades compradas
    private float $price;     // Precio unitario del producto

    public function __construct(int $productId, int $quantity, float $price) {
        // Asignamos los valores a las propiedades privadas
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    // Getters para acceder a los datos del ítem

    public function productId(): int {
        return $this->productId;
    }

    public function quantity(): int {
        return $this->quantity;
    }

    public function price(): float {
        return $this->price;
    }

    // Convierte el ítem a un array asociativo, útil para serialización o logs
    public function toArray(): array {
        return [
            "product_id" => $this->productId,
            "quantity" => $this->quantity,
            "price" => $this->price
        ];
    }
}
