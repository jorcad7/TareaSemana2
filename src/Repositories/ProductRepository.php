<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Product;
use PDO;

final class ProductRepository implements ProductRepositoryInterface
{
    private const WRITABLE = ['name', 'description', 'price', 'stock', 'active', 'created_at'];

    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $rows = $this->pdo
            ->query('SELECT * FROM "products" ORDER BY "id" DESC')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            [Product::class, 'fromArray'],
            $rows
        );
    }

    public function find(int $id): ?Product
    {
        $st = $this->pdo->prepare(
            'SELECT * FROM "products" WHERE "id" = :id'
        );

        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);

        return $row ? Product::fromArray($row) : null;
    }

    public function create(Product $entity): int
    {
        $sql = '
            INSERT INTO "products" ("name", "description", "price", "stock", "active", "created_at")
            VALUES (:name, :description, :price, :stock, :active, :created_at)
            RETURNING "id"
        ';

        $st = $this->pdo->prepare($sql);
        $st->execute($this->data($entity));

        return (int) $st->fetchColumn();
    }

    public function update(Product $entity): void
    {
        $sql = '
            UPDATE "products"
            SET "name" = :name, "description" = :description, "price" = :price, "stock" = :stock, "active" = :active, "created_at" = :created_at
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
            'DELETE FROM "products" WHERE "id" = :id'
        );

        $st->execute(['id' => $id]);
    }

    private function data(Product $entity): array
    {
        return array_intersect_key(
            $entity->toArray(),
            array_flip(self::WRITABLE)
        );
    }
}
