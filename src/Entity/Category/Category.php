<?php 

namespace Src\Entity\Category;

use DateTime;

final class Category {

    public function __construct(
        private readonly ?int $id,
        private string $name,
        private string $description,
        private DateTime $creationDate,
        private bool $deleted
    ) {
    }

    public static function create(string $name, string $description, DateTime $creationDate): self
    {
        return new self(null, $name, $description, $creationDate, false);
    }

    public function modify(string $name, string $description): void
    {
        $this->name = $name;
        $this->description = $description;
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

    public function creationDate(): DateTime
    {
        return $this->creationDate;
    }
}
