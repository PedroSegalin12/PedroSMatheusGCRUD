<?php $this->layout('admin_template', ['title' => 'Lista de Pedidos']) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Pedidos</h2>
            <a href="/admin/orders/create" class="btn btn-primary mb-3">Registrar Nova Venda</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Devedor (Fiado)</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="7">Nenhum pedido encontrado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $order['id'] ?></td>
                                <td><?= $order['user_id'] // Idealmente, buscar o nome do usuário aqui ?></td>
                                <td><?= $order['debtor_id'] ? 'Sim' : 'Não' ?></td>
                                <td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
                                <td><span class="badge bg-<?= $order['status'] == 'Pago' ? 'success' : ($order['status'] == 'Pendente' ? 'warning' : 'info') ?>"><?= $order['status'] ?></span></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <a href="/admin/orders/show?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">Ver</a>
                                    <form action="/admin/orders/delete" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja EXCLUIR este pedido e REVERTER as transações (dívida/saldo)?');">
                                        <input type="hidden" name="_csrf" value="<?= App\Core\Csrf::token() ?>">
                                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if ($pages > 1): ?>
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>