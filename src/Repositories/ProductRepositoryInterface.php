<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Product;

interface ProductRepositoryInterface
{
    /** @return Product[] */
    public function all(): array;

    public function find(int $id): ?Product;

    public function create(Product $entity): int;

    public function update(Product $entity): void;

    public function delete(int $id): void;
}
