<?php $this->layout('layouts/admin', ['title' => 'Novo Autor']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Novo Autor']) ?>
    <div class="card-body">
        <form method="post" action="/admin/autores/store" enctype="multipart/form-data" class="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome_autor" class="form-label">Nome do autor</label>
                    <input type="text" class="form-control" id="nome_autor" name="nome_autor" placeholder="Digite o nome do autor"
                           value="<?= $this->e(($old['nome_autor'] ?? '')) ?>">
                    <?php if (!empty($errors['nome_autor'])): ?>
                        <div class="text-danger"><?= $this->e($errors['nome_autor']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                           placeholder="Digite o texto" value="<?= $this->e(($old['data_nascimento'] ?? '')) ?>">
                    <?php if (!empty($errors['data_nascimento'])): ?>
                        <div class="text-danger"><?= $this->e($errors['data_nascimento']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nacionalidade" class="form-label">Nacionalidade</label>
                    <input type="text" class="form-control" id="nacionalidade" name="nacionalidade" placeholder="Digite o nacionalidade do autor"
                           value="<?= $this->e(($old['nacionalidade'] ?? '')) ?>">
                    <?php if (!empty($errors['nacionalidade'])): ?>
                        <div class="text-danger"><?= $this->e($errors['nacionalidade']) ?></div><?php endif; ?>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg"></i> Salvar
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
