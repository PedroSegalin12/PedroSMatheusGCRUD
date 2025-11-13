<h1>Lista de Pedidos</h1>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['customer_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($order['total'] ?? '') ?></td>
                    <td><?= htmlspecialchars($order['created_at'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Nenhum pedido encontrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
