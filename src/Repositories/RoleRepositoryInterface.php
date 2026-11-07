<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Role;

interface RoleRepositoryInterface
{
    /** @return Role[] */
    public function all(): array;

    public function find(int $id): ?Role;

    public function create(Role $entity): int;

    public function update(Role $entity): void;

    public function delete(int $id): void;
}
