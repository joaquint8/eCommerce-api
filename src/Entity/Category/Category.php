<?php 

namespace Src\Entity\Category;

use DateTime;

final class Category {

    public function __construct(
        private readonly ?int $id,
        private string $name,
        private DateTime $created_at,
        private DateTime $updated_at,
        private bool $deleted
    ) {
    }

    public static function create(string $name, DateTime $created_at, DateTime $updated_at): self
    {
        return new self(null, $name, $created_at, $updated_at, false);
    }

    public function modify(string $name, DateTime $updated_at): void
    {
        $this->name = $name;
        $this->updated_at = $updated_at;
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

    public function created_at(): DateTime
    {
        return $this->created_at;
    }

    public function updated_at(): DateTime
    {
        return $this->updated_at;
    }
}
