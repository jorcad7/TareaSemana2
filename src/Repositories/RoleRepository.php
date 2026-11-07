<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Role;
use PDO;

final class RoleRepository implements RoleRepositoryInterface
{
    private const WRITABLE = ['name', 'description', 'created_at'];

    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $rows = $this->pdo
            ->query('SELECT * FROM "roles" ORDER BY "id" DESC')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            [Role::class, 'fromArray'],
            $rows
        );
    }

    public function find(int $id): ?Role
    {
        $st = $this->pdo->prepare(
            'SELECT * FROM "roles" WHERE "id" = :id'
        );

        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);

        return $row ? Role::fromArray($row) : null;
    }

    public function create(Role $entity): int
    {
        $sql = '
            INSERT INTO "roles" ("name", "description", "created_at")
            VALUES (:name, :description, :created_at)
            RETURNING "id"
        ';

        $st = $this->pdo->prepare($sql);
        $st->execute($this->data($entity));

        return (int) $st->fetchColumn();
    }

    public function update(Role $entity): void
    {
        $sql = '
            UPDATE "roles"
            SET "name" = :name, "description" = :description, "created_at" = :created_at
            WHERE "id" = :__pk
        ';

        $st = $this->pdo->prepare($sql);

        $st->execute(
            $this->data($entity) + [
                '__pk' => $entity->id
            ]
        );
    }

    public function delete(int $id): void
    {
        $st = $this->pdo->prepare(
            'DELETE FROM "roles" WHERE "id" = :id'
        );

        $st->execute(['id' => $id]);
    }

    private function data(Role $entity): array
    {
        return array_intersect_key(
            $entity->toArray(),
            array_flip(self::WRITABLE)
        );
    }
}
