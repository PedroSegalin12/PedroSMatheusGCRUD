<?php $this->layout('admin_template', ['title' => 'Editar Pedido']) ?>

<div class="container-fluid">
    <h2>Editar Pedido #<?= $order['id'] ?></h2>
    
    <div class="alert alert-info">
        <strong>Atenção:</strong> A edição de pedidos normalmente se restringe a alterar o status ou registrar pagamentos. Se precisar de uma alteração de item, o recomendado é cancelar o pedido e criar um novo.
    </div>

    <form action="/admin/orders/update?id=<?= $order['id'] ?>" method="POST">
        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
        <input type="hidden" name="id" value="<?= $order['id'] ?>">

        <div class="mb-3">
            <label for="status" class="form-label">Status do Pedido</label>
            <select class="form-select" id="status" name="status">
                <option value="Pendente" <?= $order['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="Pago" <?= $order['status'] == 'Pago' ? 'selected' : '' ?>>Pago</option>
                <option value="Entregue" <?= $order['status'] == 'Entregue' ? 'selected' : '' ?>>Entregue</option>
                <option value="Cancelado" <?= $order['status'] == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>
        
        <?php if ($order['debtor_id']): ?>
            <div class="alert alert-warning">
                <strong>Pedido Fiado:</strong> Para registrar um pagamento, você deve usar a funcionalidade de "Devedores" ou "Dívidas" para garantir que o saldo seja atualizado.
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="/admin/orders" class="btn btn-secondary">Cancelar</a>
    </form>

    <h3 class="mt-5">Itens do Pedido</h3>
    </div>