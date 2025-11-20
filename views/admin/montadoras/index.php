<?php $this->layout('layouts/admin', ['title' => 'Montadoras']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="tableView">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold">Lista de Montadoras</h5>
        <a href="/admin/montadoras/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nova Montadora
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cidade</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php foreach ($montadoras as $montadora): ?>
                    <tr>
                        <td><?= $this->e($montadora['id']) ?></td>
                        <td><?= $this->e($montadora['nome']) ?></td>
                        <td><?= $this->e($montadora['cidade']) ?></td>
                        <td><?= $this->e($montadora['telefone']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a class="btn btn-sm btn-secondary" href="/admin/montadoras/show?id=<?= $this->e($montadora['id']) ?>">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a class="btn btn-sm btn-primary" href="/admin/montadoras/edit?id=<?= $this->e($montadora['id']) ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form class="inline" action="/admin/montadoras/delete" method="post"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta montadora? (<?= $this->e($montadora['nome']) ?>)');">
                                    <input type="hidden" name="id" value="<?= $this->e($montadora['id']) ?>">
                                    <?= \App\Core\Csrf::input() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Excluir
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
            <a href="/admin/montadoras?page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php $this->stop() ?>
