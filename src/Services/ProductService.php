<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\NotFoundException;
use App\Entities\Product;
use App\Repositories\ProductRepositoryInterface;

final class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {
    }

    public function list(): array
    {
        return $this->repository->all();
    }

    public function get(int $id): Product
    {
        return $this->repository->find($id)
            ?? throw new NotFoundException();
    }

    public function validate(Product $e): array
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

    public function create(Product $e): int
    {
        return $this->repository->create($e);
    }

    public function update(Product $e): void
    {
        $this->repository->update($e);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
