<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Entities\Role;
use App\Repositories\RoleRepository;
use App\Services\RoleService;
use PDOException;

final class RolesController extends Controller
{
    private RoleService $service;

    public function __construct(?RoleService $service = null)
    {
        parent::__construct();

        $this->service = $service
            ?? new RoleService(
                new RoleRepository(Database::connection())
            );
    }

    public function index(): void
    {
        $this->render(
            'roles/index',
            ['items' => $this->service->list()],
            'Roles'
        );
    }

    public function create(): void
    {
        $this->form(new Role(), [], 'roles/store', 'Nuevo Role');
    }

    public function store(): void
    {
        $this->requirePost();

        $entity = Role::fromArray($this->request->allPost());
        $errors = $this->service->validate($entity);

        if ($errors) {
            $this->form($entity, $errors, 'roles/store', 'Nuevo Role');
            return;
        }

        $this->service->create($entity);

        $this->redirect('roles', 'Registro creado correctamente.');
    }

    public function edit(?int $id = null): void
    {
        $entity = $this->service->get((int) $id);

        $this->form(
            $entity,
            [],
            'roles/update/' . $entity->id,
            'Editar Role'
        );
    }

    public function update(?int $id = null): void
    {
        $this->requirePost();

        $this->service->get((int) $id);

        $entity = Role::fromArray($this->request->allPost());
        $entity->id = (int) $id;

        $errors = $this->service->validate($entity);

        if ($errors) {
            $this->form(
                $entity,
                $errors,
                'roles/update/' . $id,
                'Editar Role'
            );
            return;
        }

        $this->service->update($entity);

        $this->redirect('roles', 'Registro actualizado correctamente.');
    }

    public function delete(?int $id = null): void
    {
        $this->requirePost();

        $this->service->get((int) $id);

        try {
            $this->service->delete((int) $id);
        } catch (PDOException) {
            $this->redirect(
                'roles',
                'No se pudo eliminar: el registro esta en uso.',
                'error'
            );
        }

        $this->redirect('roles', 'Registro eliminado.');
    }

    private function form(
        Role $entity,
        array $errors,
        string $action,
        string $title
    ): void {
        $this->render(
            'roles/form',
            compact('entity', 'errors', 'action'),
            $title
        );
    }
}
