<?php
namespace Src\Entity\Order;

use DateTime;

// Entidad que representa una orden de compra.
// Contiene información del usuario, dirección, estado, fecha y los ítems comprados.

final class Order {
    private int $id;                        // ID interno de la orden (autogenerado en la base de datos)
    private string $externalReference;     // Referencia única para vincular con Mercado Pago
    private float $total;                  // Monto total de la orden
    private string $shippingAddress;       // Dirección de envío
    private string $status;                // Estado de la orden (pending, paid, etc.)
    private DateTime $createdAt;           // Fecha de creación
    /** @var OrderItem[] */
    private array $items;                  // Lista de ítems comprados (instancias de OrderItem)

    public function __construct(
        int $id,
        string $externalReference,
        float $total,
        string $shippingAddress,
        string $status,
        DateTime $createdAt,
        array $items // debe contener instancias de OrderItem
    ) {
        // Validamos que todos los elementos del array sean instancias de OrderItem
        foreach ($items as $item) {
            if (!$item instanceof OrderItem) {
                throw new \InvalidArgumentException("Todos los ítems deben ser instancias de OrderItem");
            }
        }

        // Asignamos los valores a las propiedades privadas
        $this->id = $id;
        $this->externalReference = $externalReference;
        $this->total = $total;
        $this->shippingAddress = $shippingAddress;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->items = $items;
    }

    // Getters para acceder a los datos de la orden

    public function getId(): int {
        return $this->id;
    }

    public function externalReference(): string {
        return $this->externalReference;
    }

    public function total(): float {
        return $this->total;
    }

    public function shippingAddress(): string {
        return $this->shippingAddress;
    }

    public function status(): string {
        return $this->status;
    }

    public function createdAt(): DateTime {
        return $this->createdAt;
    }

    /** @return OrderItem[] */
    public function items(): array {
        return $this->items;
    }
}
