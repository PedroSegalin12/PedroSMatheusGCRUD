<?php $this->layout('layouts/admin', ['title' => 'Editar Moto']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Editar Moto']) ?>
    <div class="card-body">
        <form method="post" action="/admin/motos/update" class="">
            <input type="hidden" name="id" value="<?= $this->e($moto['id']) ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo"
                           value="<?= $this->e($moto['modelo'] ?? '') ?>" required>
                    <?php if (!empty($errors['modelo'])): ?>
                        <div class="text-danger"><?= $this->e($errors['modelo']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ano" class="form-label">Ano</label>
                    <input type="number" class="form-control" id="ano" name="ano"
                           value="<?= $this->e($moto['ano'] ?? '') ?>" required>
                    <?php if (!empty($errors['ano'])): ?>
                        <div class="text-danger"><?= $this->e($errors['ano']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="disponivel" name="disponivel"
                               value="1" <?= !empty($moto['disponivel']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="disponivel">Disponível</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="montadora_id" class="form-label">Montadora</label>
                    <select class="form-select" id="montadora_id" name="montadora_id" required>
                        <?php foreach ($montadoras as $montadora): ?>
                            <option value="<?= $montadora['id'] ?>" <?= $this->e(($moto['montadora_id'] ?? '') == $montadora['id'] ? 'selected' : '') ?>>
                                <?= $this->e($montadora['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['montadora_id'])): ?>
                        <div class="text-danger"><?= $this->e($errors['montadora_id']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Atualizar
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Limpar
                </button>
                <a href="/admin/motos" class="btn align-self-end">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>
            <?= \App\Core\Csrf::input() ?>
        </form>
    </div>
</div>
<?php $this->stop() ?>
