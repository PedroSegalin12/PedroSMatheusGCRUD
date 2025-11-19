<?php $this->layout('layouts/admin', ['title' => 'Empréstimos']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="tableView">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold">Lista de Empréstimos</h5>
        <a href="/admin/testdrives/create" class="btn btn-primary" id="btnNewtestdrive">
            <i class="bi bi-plus-lg"></i> Novo Empréstimo
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID Usuário</th>
                    <th>ID Livro</th>
                    <th>Data Empréstimo</th>
                    <th>Data Devolução</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php 
                // A variável deve ser $testdrives (plural) e o item deve ser $testdrive (singular)
                // O repositório está retornando objetos testdrive (PDO::FETCH_CLASS), então usamos ->propriedade
                foreach ($testdrives as $testdrive): 
                ?>
                    <tr>
                        <td><?= $this->e($testdrive->id_user) ?></td>
                        <td><?= $this->e($testdrive->id_livro) ?></td>
                        <td><?= $this->e($testdrive->data_testdrive) ?></td>
                        <td><?= $this->e($testdrive->data_devolucao ?? '—') ?></td>
                        <td>
                            <?php 
                            $badgeClass = $testdrive->status === 'devolvido' ? 'bg-success' : 'bg-warning text-dark';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $this->e(ucfirst($testdrive->status)) ?></span>
                        </td>
                        <td>
                            <div class="action-buttons d-flex gap-2">
                                <a class="btn btn-sm btn-secondary btn-edit"
                                   href="/admin/testdrives/show?id=<?= $this->e($testdrive->id) ?>">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a class="btn btn-sm btn-primary btn-edit"
                                   href="/admin/testdrives/edit?id=<?= $this->e($testdrive->id) ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form class="inline" action="/admin/testdrives/delete" method="post"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este empréstimo (ID: <?= $this->e($testdrive->id) ?>)?');">
                                    <input type="hidden" name="id" value="<?= $this->e($testdrive->id) ?>">
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
            <!-- Ajusta a rota para /admin/testdrives para manter o contexto -->
            <a href="/admin/testdrives?page=<?= $i ?>"><?= $i ?></a> 
        <?php endif; ?>
    <?php endfor; ?>
</div>

<?php $this->stop() ?>
