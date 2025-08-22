<?php 

namespace Src\Entity\Order;
use Src\Entity\Order\OrderState;

use DateTime;

// Entidad que representa una orden de compra.
// Contiene información del usuario, dirección, estado, fecha y los ítems comprados.

final class Order {

    public function __construct(
        private readonly ?int $id,  // ID interno de la orden (autogenerado en la base de datos)
        private string $externalReference, // Referencia única para vincular con Mercado Pago
        private float $total, // Monto total de la orden
        private string $shipping_address, // Dirección de envío
        private OrderState $status, // Estado de la orden (pending, paid, etc.)
        private DateTime $created_at, // Fecha de creación
        /** @var OrderItem[] */
        private array $items // Lista de ítems comprados (instancias de OrderItem)
    ) {
        // Validamos que todos los elementos del array sean instancias de OrderItem
        foreach ($items as $item) {
            if (!$item instanceof OrderItem) {
                throw new \InvalidArgumentException("Todos los ítems deben ser instancias de OrderItem");
            }
        }
    }

    // funcion para crear una nueva orden
    public static function create(string $externalReference, float $total,string $shipping_address, OrderState $status, DateTime $created_at, array $items): self
    {
        return new self(null, $externalReference, $total, $shipping_address, $status, $created_at, $items);
    }

    // Getters para acceder a los datos de la orden
    
    public function id(): ?int
    {
        return $this->id;
    }

    public function externalReference(): string
    {
        return $this->externalReference;
    }

    public function total(): float
    {
        return $this->total;
    }

    public function shipping_address(): string
    {
        return $this->shipping_address;
    }

    public function status(): OrderState
    {
        return $this->status;
    }

    public function created_at(): DateTime
    {
        return $this->created_at;
    }

    public function items(): array
    {
        return $this->items;
    }
}
