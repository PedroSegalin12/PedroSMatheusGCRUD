<?php $this->layout('layouts/admin', ['title' => 'carros']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="tableView">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold">Lista de carros</h5>
        <a href="/admin/carros/create" class="btn btn-primary" id="btnNewUser">
            <i class="bi bi-plus-lg"></i> Novo carro
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Ano de publicação</th>
                    <th>Gênero</th>
                    <th>Disponível</th>
                    <th>Editora</th>
                    <th>Autor</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php foreach ($carros as $carro): ?>
                    <tr>
                        <td><?= $this->e($carro['id']) ?></td>
                        <td><?= $this->e($carro['titulo']) ?></td>
                        <td><?= $this->e($carro['ano_publicacao']) ?></td>
                        <td><?= $this->e($carro['genero']) ?></td>
                        <td><?= $this->e($carro['disponivel'] ? 'Sim' : 'Não') ?></td>
                        <td><?= $this->e($editoras[$carro['editora_id']] ?? 'Desconhecido') ?></td>
                        <td><?= $this->e($autores[$carro['autor_id']] ?? 'Desconhecido') ?></td>
                        <td>
                            <div class="action-buttons">
                                <a class="btn btn-sm btn-secondary btn-edit"
                                   href="/admin/carros/show?id=<?= $this->e($carro['id']) ?>">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a class="btn btn-sm btn-primary btn-edit"
                                   href="/admin/carros/edit?id=<?= $this->e($carro['id']) ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form class="inline" action="/admin/carros/delete" method="post"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este carro? (<?= $this->e($carro['titulo']) ?>)');">
                                    <input type="hidden" name="id" value="<?= $this->e($carro['id']) ?>">
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
