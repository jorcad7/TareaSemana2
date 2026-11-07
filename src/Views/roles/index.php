<div class="toolbar">
    <h1>Roles</h1>
    <a class="btn" href="<?= url('roles/create') ?>">Nuevo</a>
</div>

<?php if (empty($items)): ?>
    <p>No hay registros.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
            <th>Created at</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
            <td><?= e($item->id) ?></td>
            <td><?= e($item->name) ?></td>
            <td><?= e(mb_strimwidth((string) $item->description, 0, 60, '...')) ?></td>
            <td><?= e($item->created_at) ?></td>
                <td class="actions">
                    <a href="<?= url('roles/edit/' . $item->id) ?>">Editar</a>

                    <form method="post"
                          action="<?= url('roles/delete/' . $item->id) ?>"
                          onsubmit="return confirm('Eliminar este registro?')">
                        <button class="link danger" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
