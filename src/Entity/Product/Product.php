<?php 

namespace Src\Entity\Product;

use DateTime;

use Src\Entity\Product\ProductState;

final class Product {

    public function __construct(
        private readonly ?int $id,
        private string $name,
        private string $description,
        private float $price,
        private int $stock,
        private ProductState $state,
        private DateTime $creationDate,
        private int $categoryId,
        private bool $deleted,
        private ?string $imageUrl = null
        // Agregar un capo para fecha de edicion que al principio sea null y luego se le asigne la fecha actual al momento de modificar el producto
        // private ?DateTime $modifiedDate = null
    ) {
    }

    public static function create( string $name, string $description, int $price, int $stock, ProductState $state, DateTime $creationDate, int $categoryId, string $imageUrl,): self
    {
        return new self(null, $name, $description, $price,$stock, $state, $creationDate, $categoryId, false, $imageUrl);
    }

    public function modify(string $name, string $description, float $price, int $stock, ProductState $state, int $categoryId, string $imageUrl): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->state = $state;
        $this->categoryId = $categoryId;
        $this->imageUrl = $imageUrl;
    }

    // Cambia Estado 'DELETED'
    public function delete(): void
    {
        $this->deleted = true;
    }

    // Consulta Estado 'DELETED'
    public function isDeleted(): int
    {
        return $this->deleted ? 1 : 0;
    }
    
    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function price(): int
    {
        return $this->price;
    }
    public function stock(): int
    {
        return $this->stock;
    }
    public function state(): ProductState
    {
        return $this->state;
    }
    public function imageUrl(): ?string
    {
        return $this->imageUrl;
    }
    public function creationDate(): DateTime
    {
        return $this->creationDate;
    }

    public function categoryId(): int
    {
        return $this->categoryId;
    }
}