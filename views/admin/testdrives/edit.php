<?php $this->layout('layouts/admin', ['title' => 'Editar Empréstimo']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Editar Empréstimo']) ?>
    <div class="card-body">
        <form method="post" action="/admin/testdrives/update" class="">
            <input type="hidden" name="id_testdrives" value="<?= $this->e($testdrive['id_testdrives']) ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_user" class="form-label">Usuário</label>
                    <select class="form-control" id="id_user" name="id_user" required>
                        <option value="">Selecione o Usuário</option>
                        <?php $current_user_id = $testdrive['id_user'] ?? ''; ?>
                        <?php foreach ($users as $user): // Assumindo que $users é um array com os usuários ?>
                            <option value="<?= $this->e($user['id']) ?>"
                                <?= $current_user_id == $user['id'] ? 'selected' : '' ?>>
                                <?= $this->e($user['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['id_user'])): ?>
                        <div class="text-danger"><?= $this->e($errors['id_user']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="id_livro" class="form-label">Livro</label>
                    <select class="form-control" id="id_livro" name="id_livro" required>
                        <option value="">Selecione o Livro</option>
                        <?php $current_livro_id = $testdrive['id_livro'] ?? ''; ?>
                        <?php foreach ($livros as $livro): // Assumindo que $livros é um array com os livros ?>
                            <option value="<?= $this->e($livro['id_livro']) ?>"
                                <?= $current_livro_id == $livro['id_livro'] ? 'selected' : '' ?>>
                                <?= $this->e($livro['titulo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['id_livro'])): ?>
                        <div class="text-danger"><?= $this->e($errors['id_livro']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="data_testdrive" class="form-label">Data de Empréstimo</label>
                    <input type="date" class="form-control" id="data_testdrive" name="data_testdrive"
                           value="<?= $this->e(($testdrive['data_testdrive'] ?? '')) ?>" required>
                    <?php if (!empty($errors['data_testdrive'])): ?>
                        <div class="text-danger"><?= $this->e($errors['data_testdrive']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="data_devolucao" class="form-label">Data de Devolução</label>
                    <input type="date" class="form-control" id="data_devolucao" name="data_devolucao"
                           value="<?= $this->e(($testdrive['data_devolucao'] ?? '')) ?>">
                    <?php if (!empty($errors['data_devolucao'])): ?>
                        <div class="text-danger"><?= $this->e($errors['data_devolucao']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <?php $current_status = $testdrive['status'] ?? 'emprestado'; ?>
                        <option value="emprestado" <?= $current_status == 'emprestado' ? 'selected' : '' ?>>Emprestado</option>
                        <option value="devolvido" <?= $current_status == 'devolvido' ? 'selected' : '' ?>>Devolvido</option>
                    </select>
                    <?php if (!empty($errors['status'])): ?>
                        <div class="text-danger"><?= $this->e($errors['status']) ?></div>
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
                <a href="/admin/testdrives" class="btn align-self-end">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>
            <?= \App\Core\Csrf::input() ?>
        </form>
    </div>
</div>
<?php $this->stop() ?>
