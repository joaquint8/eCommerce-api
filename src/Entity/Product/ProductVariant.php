<?php

namespace Src\Entity\Product;
use Src\Entity\Product\ProductState;
use Src\Entity\Product\ProductColor;
use Src\Entity\Product\ProductSize;
final class ProductVariant
{
    public function __construct(
        private ?int $id,
        private int $productId,
        private ProductColor $color,
        private ProductSize $size,
        private int $stock,
        private ProductState $state,
        private bool $deleted
    ) {}

    public function id(): int {
        return $this->id;
    }

    public function productId(): int {
        return $this->productId;
    }

    public function color(): ProductColor {
        return $this->color;
    }

    public function size(): ProductSize {
        return $this->size;
    }

    public function stock(): int {
        return $this->stock;
    }

    public function isDeleted(): int
    {
        return $this->deleted ? 1 : 0;
    }

    public function state(): ProductState {
        return $this->state;
    }
}
