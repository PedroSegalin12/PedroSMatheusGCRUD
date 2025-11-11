<?php $this->layout('admin_template', ['title' => 'Nova Venda']) ?>

<div class="container-fluid">
    <h2>Registrar Nova Venda</h2>

    <form action="/admin/orders/store" method="POST" id="order-form">
        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Informações da Transação</div>
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Método de Pagamento</label>
                            <select class="form-select" id="payment_method" name="payment_method">
                                <option value="dinheiro" selected>Dinheiro/Pix</option>
                                <option value="fiado">Fiado</option>
                            </select>
                            <?php if (isset($errors['payment_method'])): ?><div class="text-danger"><?= $errors['payment_method'] ?></div><?php endif; ?>
                        </div>
                        
                        <div class="mb-3" id="debtor-select-container" style="display:none;">
                            <label for="debtor_id" class="form-label">Selecionar Devedor</label>
                            <select class="form-select" id="debtor_id" name="debtor_id">
                                <option value="0">Selecione o Devedor...</option>
                                <?php foreach ($debtors as $debtor): ?>
                                    <option value="<?= $debtor['id'] ?>"><?= $debtor['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['debtor_id'])): ?><div class="text-danger"><?= $errors['debtor_id'] ?></div><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">Adicionar Lanche</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="product_select" class="form-label">Lanche / Produto</label>
                            <select class="form-select" id="product_select">
                                <option value="">Selecione um produto...</option>
                                <?php foreach ($products as $product): ?>
                                    <option 
                                        value="<?= $product['id'] ?>" 
                                        data-price="<?= $product['price'] ?>"
                                        data-name="<?= $product['name'] ?>"
                                    >
                                        <?= $product['name'] ?> (R$ <?= number_format($product['price'], 2, ',', '.') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="product_qty" class="form-label">Qtd</label>
                                    <input type="number" id="product_qty" class="form-control" value="1" min="1">
                                </div>
                            </div>
                            <div class="col-6 d-flex align-items-end">
                                <button type="button" id="add-item-btn" class="btn btn-success w-100">Adicionar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">Itens do Pedido (<span id="total-items">0</span>)</div>
            <div class="card-body">
                <table class="table" id="items-table">
                    <thead>
                        <tr>
                            <th>Lanche</th>
                            <th>Preço Unit.</th>
                            <th>Qtd.</th>
                            <th>Subtotal</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                            <td id="order-total" class="fw-bold">R$ 0,00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php if (isset($errors['items'])): ?><div class="alert alert-danger"><?= $errors['items'] ?></div><?php endif; ?>
        
        <button type="submit" class="btn btn-lg btn-primary w-100">Finalizar Venda</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.getElementById('payment_method');
    const debtorContainer = document.getElementById('debtor-select-container');
    const debtorSelect = document.getElementById('debtor_id');
    const addItemBtn = document.getElementById('add-item-btn');
    const productSelect = document.getElementById('product_select');
    const productQty = document.getElementById('product_qty');
    const itemsTableBody = document.querySelector('#items-table tbody');
    const orderTotalDisplay = document.getElementById('order-total');
    const totalItemsDisplay = document.getElementById('total-items');
    let orderItems = [];

    // Lógica para mostrar/esconder a seleção de devedor
    paymentMethod.addEventListener('change', function() {
        if (this.value === 'fiado') {
            debtorContainer.style.display = 'block';
        } else {
            debtorContainer.style.display = 'none';
            // Opcional: resetar a seleção do devedor
            debtorSelect.value = '0';
        }
    });

    // Função para calcular e atualizar o total
    function updateOrderTotal() {
        let total = 0;
        orderItems.forEach(item => {
            total += item.price_at_sale * item.quantity;
        });
        
        orderTotalDisplay.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
        totalItemsDisplay.textContent = orderItems.length;

        // Limpar a tabela antes de redesenhar
        itemsTableBody.innerHTML = ''; 

        orderItems.forEach((item, index) => {
            const subtotal = item.price_at_sale * item.quantity;
            const row = itemsTableBody.insertRow();
            
            row.innerHTML = `
                <td>${item.product_name}</td>
                <td>R$ ${item.price_at_sale.toFixed(2).replace('.', ',')}</td>
                <td>
                    <input type="number" name="items[${index}][quantity]" value="${item.quantity}" min="1" class="form-control form-control-sm item-qty-input" data-index="${index}" style="width: 70px;">
                    <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${index}][price_at_sale]" value="${item.price_at_sale}">
                </td>
                <td>R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
                <td><button type="button" class="btn btn-sm btn-danger remove-item-btn" data-index="${index}">Remover</button></td>
            `;
        });
        
        attachEventListeners();
    }
    
    // Função para anexar listeners para remoção e atualização de quantidade
    function attachEventListeners() {
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                orderItems.splice(index, 1);
                updateOrderTotal();
            });
        });
        
        document.querySelectorAll('.item-qty-input').forEach(input => {
            input.addEventListener('change', function() {
                const index = parseInt(this.getAttribute('data-index'));
                orderItems[index].quantity = parseInt(this.value);
                updateOrderTotal();
            });
        });
    }


    // Lógica para adicionar item
    addItemBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productId = parseInt(selectedOption.value);
        const productName = selectedOption.getAttribute('data-name');
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const quantity = parseInt(productQty.value);

        if (!productId || quantity <= 0) {
            alert('Selecione um produto e uma quantidade válida.');
            return;
        }
        
        // Verificar se o item já está no carrinho
        const existingItemIndex = orderItems.findIndex(item => item.product_id === productId);

        if (existingItemIndex > -1) {
            // Se existir, apenas atualiza a quantidade
            orderItems[existingItemIndex].quantity += quantity;
        } else {
            // Se não existir, adiciona como novo
            orderItems.push({
                product_id: productId,
                product_name: productName,
                price_at_sale: price,
                quantity: quantity
            });
        }
        
        // Resetar campos de seleção
        productSelect.value = '';
        productQty.value = 1;
        
        updateOrderTotal();
    });
});
</script>