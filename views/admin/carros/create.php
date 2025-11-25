<?php $this->layout('layouts/admin', ['title' => 'Novo Carro']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Novo Carro']) ?>
    <div class="card-body">
        <form method="post" action="/admin/carros/store" enctype="multipart/form-data" class="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="titulo" class="form-label">Título do Carro</label>
                    <input type="text" class="form-control" id="titulo" name="titulo"
                        placeholder="Digite o título do carro" value="<?= $this->e($old['titulo'] ?? '') ?>" required>
                    <?php if (!empty($errors['titulo'])): ?>
                        <div class="text-danger"><?= $this->e($errors['titulo']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ano_publicacao" class="form-label">Ano de Publicação</label>
                    <input type="number" class="form-control" id="ano_publicacao" name="ano_publicacao"
                        placeholder="Digite o ano de publicação" value="<?= $this->e($old['ano_publicacao'] ?? '') ?>">
                    <?php if (!empty($errors['ano_publicacao'])): ?>
                        <div class="text-danger"><?= $this->e($errors['ano_publicacao']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="genero" class="form-label">Gênero</label>
                    <input type="text" class="form-control" id="genero" name="genero"
                        placeholder="Ex: SUV, Hatch" value="<?= $this->e($old['genero'] ?? '') ?>">
                    <?php if (!empty($errors['genero'])): ?>
                        <div class="text-danger"><?= $this->e($errors['genero']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Montadora_id" class="form-label">Montadora</label>
                    <select class="form-select" id="Montadora_id" name="Montadora_id" required>
                        <option value="">Selecione uma Montadora</option>
                        <?php foreach ($Montadoras as $Montadora): ?>
                            <option value="<?= $Montadora['id'] ?>" <?= $this->e(($old['Montadora_id'] ?? '') == $Montadora['id'] ? 'selected' : '') ?>>
                                <?= $this->e($Montadora['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['Montadora_id'])): ?>
                        <div class="text-danger"><?= $this->e($errors['Montadora_id']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="disponivel" name="disponivel"
                    value="1" <?= !empty($old['disponivel']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="disponivel">Disponível</label>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg"></i> Salvar
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
