<?php $this->layout('layouts/admin', ['title' => 'Nova Montadora']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Nova Montadora']) ?>
    <div class="card-body">
        <form method="post" action="/admin/Montadoras/store" class="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome"
                           value="<?= $this->e($old['nome'] ?? '') ?>" required>
                    <?php if (!empty($errors['nome'])): ?>
                        <div class="text-danger"><?= $this->e($errors['nome']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Digite a cidade"
                           value="<?= $this->e($old['cidade'] ?? '') ?>" required>
                    <?php if (!empty($errors['cidade'])): ?>
                        <div class="text-danger"><?= $this->e($errors['cidade']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Digite o telefone"
                           value="<?= $this->e($old['telefone'] ?? '') ?>" required>
                    <?php if (!empty($errors['telefone'])): ?>
                        <div class="text-danger"><?= $this->e($errors['telefone']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg"></i> Salvar
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Limpar
                </button>
                <a href="/admin/Montadoras" class="btn align-self-end">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>

            <?= \App\Core\Csrf::input() ?>
        </form>
    </div>
</div>
<?php $this->stop() ?>
