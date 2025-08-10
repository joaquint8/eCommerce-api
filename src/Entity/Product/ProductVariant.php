<?php

namespace Src\Entity\Product;
use Src\Entity\Product\ProductState;
final class ProductVariant
{
    public function __construct(
        private int $id,
        private int $productId,
        private string $color,
        private string $size,
        private int $stock,
        private ProductState $state,
        private bool $deleted
    ) {}

    // Getters
    public function id(): int {
        return $this->id;
    }

    public function productId(): int {
        return $this->productId;
    }

    public function color(): string {
        return $this->color;
    }

    public function size(): string {
        return $this->size;
    }

    public function stock(): int {
        return $this->stock;
    }

    public function isDeleted(): bool {
        return $this->deleted;
    }

    public function state(): ProductState {
        return $this->state;
    }
}
