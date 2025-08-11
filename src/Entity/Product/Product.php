<?php 

namespace Src\Entity\Product;

use DateTime;

final class Product {
    /** @var ProductVariant[] */
    private array $variants; 

    /** @var ProductVariant[] */
    private array $images; 

    public function __construct(
        private ?int $id,
        private string $name,
        private string $description,
        private float $price,
        private int $categoryId,
        private bool $deleted,
        private DateTime $created_at,
        private DateTime $updated_at,
        array $variants = [],
        array $images = [] 
    ) {
        $this->variants = $variants;
        $this->images = $images;
    }
    public static function create(
        string $name,
        string $description,
        float $price,
        int $categoryId
    ): self {
        return new self(
            null,
            $name,
            $description,
            $price,
            $categoryId,
            false, // No eliminado al crear
            new DateTime(),
            new DateTime(),
            [],  // sin variantes al crear
            []  // sin imagenes al crear ??
        );
    }

    public function modify(
        string $name,
        string $description,
        float $price,
        int $categoryId
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->updated_at = new DateTime(); // actualizar fecha
    }

    public function delete(): void {
        $this->deleted = true;
        $this->updated_at = new DateTime();
    }

    //setters para que el array no esté vacío en el ProductsGetController
    public function setVariants(array $variants): void {
        $this->variants = $variants;
    }

    public function setImages(array $images): void {
        $this->images = $images;
    }

    
    
    // === Getters ===
    public function getVariants(): array {
        return $this->variants;
    }
    public function getImages(): array {
        return $this->images;
    }
    public function isDeleted(): int
    {
        return $this->deleted ? 1 : 0;
    }
    public function id(): ?int {
        return $this->id;
    }

    public function name(): string {
        return $this->name;
    }

    public function description(): string {
        return $this->description;
    }

    public function price(): float {
        return $this->price;
    }

    public function created_at(): DateTime {
        return $this->created_at;
    }

    public function updated_at(): DateTime {
        return $this->updated_at;
    }

    public function categoryId(): int {
        return $this->categoryId;
    }
}
