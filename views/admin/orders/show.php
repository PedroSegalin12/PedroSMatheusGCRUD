<?php $this->layout('admin_template', ['title' => 'Detalhes do Pedido']) ?>

<div class="container-fluid">
    <h2>Detalhes do Pedido #<?= $order['id'] ?></h2>
    
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">Resumo</div>
        <div class="card-body">
            <p><strong>Usuário (Caixa):</strong> <?= $order['user_id'] // Se possível, mostrar nome do usuário ?></p>
            <p><strong>Data da Venda:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            <p>
                <strong>Status:</strong> 
                <span class="badge bg-<?= $order['status'] == 'Pago' ? 'success' : ($order['status'] == 'Pendente' ? 'warning' : 'secondary') ?>"><?= $order['status'] ?></span>
            </p>
            <?php if ($order['debtor_id']): ?>
                <p><strong>Devedor (Fiado):</strong> <?= $order['debtor_id'] // Se possível, mostrar nome do devedor ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Itens Vendidos</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd.</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $orderTotal = 0;
                        // Assumindo que $order['items'] é um array de itens carregados pelo OrderRepository::findWithDetails()
                        $items = $order['items'] ?? []; 
                    ?>
                    <?php if (empty($items)): ?>
                        <tr><td colspan="4">Nenhum item encontrado para este pedido.</td></tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): 
                            $subtotal = $item['price_at_sale'] * $item['quantity'];
                            $orderTotal += $subtotal;
                        ?>
                        <tr>
                            <td><?= $item['product_name'] ?? 'ID ' . $item['product_id'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>R$ <?= number_format($item['price_at_sale'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">TOTAL DO PEDIDO:</td>
                        <td class="fw-bold">R$ <?= number_format($orderTotal, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>