<h1><?= e($title) ?></h1>

<form method="post" action="<?= url($action) ?>">
    <div class="field">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" maxlength="120" value="<?= e($entity->name) ?>" required>
        <?php if (isset($errors['name'])): ?>
            <small class="error"><?= e($errors['name']) ?></small>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"><?= e($entity->description) ?></textarea>
        <?php if (isset($errors['description'])): ?>
            <small class="error"><?= e($errors['description']) ?></small>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="0.01" value="<?= e($entity->price) ?>">
        <?php if (isset($errors['price'])): ?>
            <small class="error"><?= e($errors['price']) ?></small>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" step="1" value="<?= e($entity->stock) ?>">
        <?php if (isset($errors['stock'])): ?>
            <small class="error"><?= e($errors['stock']) ?></small>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="active">Active</label>
        <input type="checkbox" id="active" name="active" value="1" <?= $entity->active ? 'checked' : '' ?>>
        <?php if (isset($errors['active'])): ?>
            <small class="error"><?= e($errors['active']) ?></small>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="created_at">Created at</label>
        <input type="datetime-local" id="created_at" name="created_at" value="<?= e($entity->created_at ? str_replace(' ', 'T', substr((string) $entity->created_at, 0, 16)) : '') ?>">
        <?php if (isset($errors['created_at'])): ?>
            <small class="error"><?= e($errors['created_at']) ?></small>
        <?php endif; ?>
    </div>

    <div class="buttons">
        <button class="btn" type="submit">Guardar</button>
        <a href="<?= url('products') ?>">Cancelar</a>
    </div>
</form>
