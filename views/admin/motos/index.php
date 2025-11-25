<?php $this->layout('layouts/admin', ['title' => 'Motos']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="tableView">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold">Lista de Motos</h5>
        <a href="/admin/motos/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nova Moto
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Modelo</th>
                        <th>Ano</th>
                        <th>Disponível</th>
                        <th>Montadora</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($motos as $moto): ?>
                        <tr>
                            <td><?= $this->e($moto['id']) ?></td>
                            <td><?= $this->e($moto['modelo']) ?></td>
                            <td><?= $this->e($moto['ano']) ?></td>
                            <td><?= $this->e($moto['disponivel'] ? 'Sim' : 'Não') ?></td>
                            <td><?= $this->e($Montadoras[$moto['Montadora_id']]['nome'] ?? 'Desconhecida') ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a class="btn btn-sm btn-secondary" href="/admin/motos/show?id=<?= $this->e($moto['id']) ?>">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a class="btn btn-sm btn-primary" href="/admin/motos/edit?id=<?= $this->e($moto['id']) ?>">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <form class="inline" action="/admin/motos/delete" method="post"
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta moto? (<?= $this->e($moto['modelo']) ?>)');">
                                        <input type="hidden" name="id" value="<?= $this->e($moto['id']) ?>">
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
            <a href="/admin/motos?page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php $this->stop() ?>
