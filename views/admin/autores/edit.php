<?php $this->layout('layouts/admin', ['title' => 'Editar Autor']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Editar Autor']) ?>
    <div class="card-body">
        <form method="post" action="/admin/autores/update" enctype="multipart/form-data" class="">
            <input type="hidden" name="id" value="<?= $this->e($autor['id']) ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome_autor" class="form-label">Nome do Autor</label>
                    <input type="text" class="form-control" id="nome_autor" name="nome_autor" placeholder="Digite o nome do autor"
                           value="<?= $this->e(($autor['nome_autor'] ?? '')) ?>" required>
                    <?php if (!empty($errors['nome_autor'])): ?>
                        <div class="text-danger"><?= $this->e($errors['nome_autor']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                            value="<?= $this->e(($autor['data_nascimento'] ?? '')) ?>">
                    <?php if (!empty($errors['data_nascimento'])): ?>
                        <div class="text-danger"><?= $this->e($errors['data_nascimento']) ?></div><?php endif; ?>
                </div>
                                <div class="col-md-6 mb-3">
                    <label for="nome_autor" class="form-label">Nacionalidade</label>
                    <input type="text" class="form-control" id="nacionalidade" name="nacionalidade" placeholder="Digite a nacionalidade do autor"
                           value="<?= $this->e(($autor['nacionalidade'] ?? '')) ?>" required>
                    <?php if (!empty($errors['nacionalidade'])): ?>
                        <div class="text-danger"><?= $this->e($errors['nacionalidade']) ?></div><?php endif; ?>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Atualizar
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Limpar
                </button>
                <a href="/admin/autores" class="btn align-self-end">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>
            <?= \App\Core\Csrf::input() ?>
        </form>
    </div>
</div>

<?php $this->stop() ?>
