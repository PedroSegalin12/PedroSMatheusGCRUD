<?php $this->layout('layouts/admin', ['title' => 'Carros']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="tableView">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold">Lista de Carros</h5>
        <a href="/admin/carros/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Novo Carro
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ano</th>
                    <th>Categoria</th>
                    <th>Disponível</th>
                    <th>Montadora</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php foreach ($carros as $carro): ?>
                    <tr>
                        <td><?= $this->e($carro['id']) ?></td>
                        <td><?= $this->e($carro['nome']) ?></td>
                        <td><?= $this->e($carro['ano']) ?></td>
                        <td><?= $this->e($carro['categoria']) ?></td>
                        <td><?= $this->e($carro['disponivel'] ? 'Sim' : 'Não') ?></td>
                        <td><?= $this->e($montadoras[$carro['montadora_id']] ?? 'Desconhecido') ?></td>
                        <td>
                            <div class="action-buttons">
                                <a class="btn btn-sm btn-secondary"
                                   href="/admin/carros/show?id=<?= $this->e($carro['id']) ?>">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a class="btn btn-sm btn-primary"
                                   href="/admin/carros/edit?id=<?= $this->e($carro['id']) ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form class="inline" action="/admin/carros/delete" method="post"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este carro? (<?= $this->e($carro['nome']) ?>)');">
                                    <input type="hidden" name="id" value="<?= $this->e($carro['id']) ?>">
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
            <a href="/admin/carros?page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php $this->stop() ?>
