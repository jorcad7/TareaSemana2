<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\NotFoundException;
use App\Entities\Role;
use App\Repositories\RoleRepositoryInterface;

final class RoleService
{
    public function __construct(
        private RoleRepositoryInterface $repository
    ) {
    }

    public function list(): array
    {
        return $this->repository->all();
    }

    public function get(int $id): Role
    {
        return $this->repository->find($id)
            ?? throw new NotFoundException();
    }

    public function validate(Role $e): array
    {
        $errors = [];

        if (trim((string) $e->name) === '') {
            $errors['name'] = 'Name es obligatorio.';
        }
        if (mb_strlen((string) $e->name) > 120) {
            $errors['name'] = 'Name admite maximo 120 caracteres.';
        }

        return $errors;
    }

    public function create(Role $e): int
    {
        return $this->repository->create($e);
    }

    public function update(Role $e): void
    {
        $this->repository->update($e);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
