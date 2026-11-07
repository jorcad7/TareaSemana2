<?php
declare(strict_types=1);

namespace App\Entities;

/** Una fila de la tabla roles. */
final class Role
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public ?string $description = null,
        public string $created_at = '',
    ) {
    }

    public static function fromArray(array $d): self
    {
        return new self(
            id: (isset($d['id']) && $d['id'] !== '' ? (int) $d['id'] : null),
            name: (string) ($d['name'] ?? ''),
            description: (isset($d['description']) && $d['description'] !== '' ? (string) $d['description'] : null),
            created_at: (string) ($d['created_at'] ?? ''),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
