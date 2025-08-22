<?php

namespace Src\Entity\OrderDetail;

use DateTime;

final class OrderDetail {

    public function __construct(
        private readonly ?int $id,
        private int $order_id,
        private int $product_id,
        private int $quantity,
        private float $unit_price,
        private float $total_price,
        private DateTime $created_at,
        private DateTime $updated_at,
    ) {
    }

    public static function create(int $order_id,int $product_id, int $quantity,float $unit_price,float $total_price, DateTime $created_at,DateTime $updated_at): self
    {
        return new self(null, $order_id, $product_id,$quantity, $unit_price,$total_price,$created_at,$updated_at);
    }

    
    public function id(): ?int
    {
        return $this->id;
    }

    public function order_id(): int
    {
        return $this->order_id;
    }

    public function product_id(): int
    {
        return $this->product_id;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function unit_price(): float
    {
        return $this->unit_price;
    }
    
    public function total_price(): float
    {
        return $this->total_price;
    }
    
    public function created_at(): DateTime
    {
        return $this->created_at;
    }

    public function updated_at(): DateTime
    {
        return $this->updated_at;
    }

}
