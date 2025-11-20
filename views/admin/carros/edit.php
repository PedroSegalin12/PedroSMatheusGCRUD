<?php $this->layout('layouts/admin', ['title' => 'Editar Carro']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Editar Carro']) ?>
    <div class="card-body">
        <form method="post" action="/admin/carros/update" enctype="multipart/form-data" class="">
            <input type="hidden" name="id" value="<?= $this->e($carro['id']) ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome do Carro</label>
                    <input type="text" class="form-control" id="nome" name="nome"
                        placeholder="Digite o nome do carro" value="<?= $this->e(($carro['nome'] ?? '')) ?>" required>
                    <?php if (!empty($errors['nome'])): ?>
                        <div class="text-danger"><?= $this->e($errors['nome']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ano" class="form-label">Ano do Carro</label>
                    <input type="number" class="form-control" id="ano" name="ano"
                        placeholder="Digite o ano do carro" value="<?= $this->e(($carro['ano'] ?? '')) ?>" required>
                    <?php if (!empty($errors['ano'])): ?>
                        <div class="text-danger"><?= $this->e($errors['ano']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <input type="text" class="form-control" id="categoria" name="categoria"
                        placeholder="Ex: SUV, Hatch" value="<?= $this->e(($carro['categoria'] ?? '')) ?>" required>
                    <?php if (!empty($errors['categoria'])): ?>
                        <div class="text-danger"><?= $this->e($errors['categoria']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="montadora_id" class="form-label">Montadora</label>
                    <select class="form-select" id="montadora_id" name="montadora_id" required>
                        <option value="">Selecione uma montadora</option>
                        <?php foreach ($montadoras as $montadora): ?>
                            <option value="<?= $montadora['id'] ?>" <?= $this->e(($carro['montadora_id'] ?? '') == $montadora['id'] ? 'selected' : '') ?>>
                                <?= $this->e($montadora['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['montadora_id'])): ?>
                        <div class="text-danger"><?= $this->e($errors['montadora_id']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="disponivel" name="disponivel"
                    value="1" <?= !empty($carro['disponivel']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="disponivel">Disponível</label>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Atualizar
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Limpar
                </button>
                <a href="/admin/carros" class="btn align-self-end">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>
            <?= \App\Core\Csrf::input() ?>
        </form>
    </div>
</div>
<?php $this->stop() ?>
