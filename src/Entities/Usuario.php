<?php
declare(strict_types=1);

namespace App\Entities;

/** Una fila de la tabla usuarios. */
final class Usuario
{
    public function __construct(
        public ?int $id = null,
        public string $login = '',
        public string $password = '',
        public string $email = '',
        public string $created_at = '',
    ) {
    }

    public static function fromArray(array $d): self
    {
        return new self(
            id: (isset($d['id']) && $d['id'] !== '' ? (int) $d['id'] : null),
            login: (string) ($d['login'] ?? ''),
            password: (string) ($d['password'] ?? ''),
            email: (string) ($d['email'] ?? ''),
            created_at: (string) ($d['created_at'] ?? ''),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'email' => $this->email,
            'created_at' => $this->created_at,
        ];
    }
}
