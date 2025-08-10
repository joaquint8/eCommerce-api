<?php

namespace Src\Entity\Product;
use DateTime;
final class ProductImages
{
    public function __construct(
        private int $id,
        private int $productId,
        private string $image_url,
        private DateTime $created_at,
        private DateTime $updated_at,
        private bool $deleted
    ) {}

    // Getters
    public function id(): int {
        return $this->id;
    }

    public function productId(): int {
        return $this->productId;
    }

    public function image_url(): string {
        return $this->image_url;
    }

    public function created_at(): DateTime {
        return $this->created_at;
    }

    public function updated_at(): DateTime {
        return $this->updated_at;
    }
    public function isDeleted(): bool {
        return $this->deleted;
    }

}
