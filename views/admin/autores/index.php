<?php $this->layout('layouts/admin', ['title' => 'Autores']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="tableView">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold">Lista de Autores</h5>
        <a href="/admin/autores/create" class="btn btn-primary" id="btnNewUser">
            <i class="bi bi-plus-lg"></i> Novo Autor
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome do Autor</th>
                    <th>Data de Nascimento</th>
                    <th>Nacionalidade</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php foreach ($autores as $autor): ?>
                    <tr>
                        <td><?= $this->e($autor['id']) ?></td>
                        <td><?= $this->e($autor['nome_autor']) ?></td>
                        <td><?= $this->e($autor['data_nascimento']) ?></td>
                        <td><?= $this->e($autor['nacionalidade']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a class="btn btn-sm btn-secondary btn-edit"
                                   href="/admin/autores/show?id=<?= $this->e($autor['id']) ?>">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a class="btn btn-sm btn-primary btn-edit"
                                   href="/admin/autores/edit?id=<?= $this->e($autor['id']) ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form class="inline" action="/admin/autores/delete" method="post"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este autor? (<?= $this->e($autor['nome_autor']) ?>)');">
                                    <input type="hidden" name="id" value="<?= $this->e($autor['id']) ?>">
                                    <?= \App\Core\Csrf::input() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="pagination" style="margin-top:12px;">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php if ($i == $page): ?>
            <strong>[<?= $i ?>]</strong>
        <?php else: ?>
            <a href="/?page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>

<?php $this->stop() ?>
