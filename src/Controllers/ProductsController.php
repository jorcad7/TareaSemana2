<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Entities\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use PDOException;

final class ProductsController extends Controller
{
    private ProductService $service;

    public function __construct(?ProductService $service = null)
    {
        parent::__construct();

        $this->service = $service
            ?? new ProductService(
                new ProductRepository(Database::connection())
            );
    }

    public function index(): void
    {
        $this->render(
            'products/index',
            ['items' => $this->service->list()],
            'Products'
        );
    }

    public function create(): void
    {
        $this->form(new Product(), [], 'products/store', 'Nuevo Product');
    }

    public function store(): void
    {
        $this->requirePost();

        $entity = Product::fromArray($this->request->allPost());
        $errors = $this->service->validate($entity);

        if ($errors) {
            $this->form($entity, $errors, 'products/store', 'Nuevo Product');
            return;
        }

        $this->service->create($entity);

        $this->redirect('products', 'Registro creado correctamente.');
    }

    public function edit(?int $id = null): void
    {
        $entity = $this->service->get((int) $id);

        $this->form(
            $entity,
            [],
            'products/update/' . $entity->id,
            'Editar Product'
        );
    }

    public function update(?int $id = null): void
    {
        $this->requirePost();

        $this->service->get((int) $id);

        $entity = Product::fromArray($this->request->allPost());
        $entity->id = (int) $id;

        $errors = $this->service->validate($entity);

        if ($errors) {
            $this->form(
                $entity,
                $errors,
                'products/update/' . $id,
                'Editar Product'
            );
            return;
        }

        $this->service->update($entity);

        $this->redirect('products', 'Registro actualizado correctamente.');
    }

    public function delete(?int $id = null): void
    {
        $this->requirePost();

        $this->service->get((int) $id);

        try {
            $this->service->delete((int) $id);
        } catch (PDOException) {
            $this->redirect(
                'products',
                'No se pudo eliminar: el registro esta en uso.',
                'error'
            );
        }

        $this->redirect('products', 'Registro eliminado.');
    }

    private function form(
        Product $entity,
        array $errors,
        string $action,
        string $title
    ): void {
        $this->render(
            'products/form',
            compact('entity', 'errors', 'action'),
            $title
        );
    }
}
