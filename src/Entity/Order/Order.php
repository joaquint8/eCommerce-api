<?php 

namespace Src\Entity\Order;
use Src\Entity\Order\OrderState;

use DateTime;

final class Order {

    public function __construct(
        private readonly ?int $id,
        private float $total,
        private string $shipping_address,
        private OrderState $status,
        private DateTime $created_at,
    ) {
    }

    public static function create(float $total,string $shipping_address, OrderState $status, DateTime $created_at): self
    {
        return new self(null, $total, $shipping_address,$status, $created_at);
    }

    
    public function id(): ?int
    {
        return $this->id;
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

}
