<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Entities\Usuario;
use App\Repositories\UsuarioRepository;
use App\Services\UsuarioService;
use PDOException;

final class UsuariosController extends Controller
{
    private UsuarioService $service;

    public function __construct(?UsuarioService $service = null)
    {
        parent::__construct();

        $this->service = $service
            ?? new UsuarioService(
                new UsuarioRepository(Database::connection())
            );
    }

    public function index(): void
    {
        $this->render(
            'usuarios/index',
            ['items' => $this->service->list()],
            'Usuarios'
        );
    }

    public function create(): void
    {
        $this->form(new Usuario(), [], 'usuarios/store', 'Nuevo Usuario');
    }

    public function store(): void
    {
        $this->requirePost();

        $entity = Usuario::fromArray($this->request->allPost());
        $errors = $this->service->validate($entity);

        if ($errors) {
            $this->form($entity, $errors, 'usuarios/store', 'Nuevo Usuario');
            return;
        }

        $this->service->create($entity);

        $this->redirect('usuarios', 'Registro creado correctamente.');
    }

    public function edit(?int $id = null): void
    {
        $entity = $this->service->get((int) $id);

        $this->form(
            $entity,
            [],
            'usuarios/update/' . $entity->id,
            'Editar Usuario'
        );
    }

    public function update(?int $id = null): void
    {
        $this->requirePost();

        $this->service->get((int) $id);

        $entity = Usuario::fromArray($this->request->allPost());
        $entity->id = (int) $id;

        $errors = $this->service->validate($entity);

        if ($errors) {
            $this->form(
                $entity,
                $errors,
                'usuarios/update/' . $id,
                'Editar Usuario'
            );
            return;
        }

        $this->service->update($entity);

        $this->redirect('usuarios', 'Registro actualizado correctamente.');
    }

    public function delete(?int $id = null): void
    {
        $this->requirePost();

        $this->service->get((int) $id);

        try {
            $this->service->delete((int) $id);
        } catch (PDOException) {
            $this->redirect(
                'usuarios',
                'No se pudo eliminar: el registro esta en uso.',
                'error'
            );
        }

        $this->redirect('usuarios', 'Registro eliminado.');
    }

    private function form(
        Usuario $entity,
        array $errors,
        string $action,
        string $title
    ): void {
        $this->render(
            'usuarios/form',
            compact('entity', 'errors', 'action'),
            $title
        );
    }
}
