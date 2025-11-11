<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
// use App\Models\Debt; // COMENTADO: Classes de Fiado não existem ainda
use App\Repositories\OrderRepository;
use App\Repositories\OrderItemRepository;
// use App\Repositories\DebtorRepository; // COMENTADO: Classes de Fiado não existem ainda
// use App\Repositories\DebtRepository;     // COMENTADO: Classes de Fiado não existem ainda
use App\Core\Database; // Assumindo que o namespace do Database está correto

class OrderService {
    
    private OrderRepository $orderRepo;
    private OrderItemRepository $orderItemRepo;
    // private DebtorRepository $debtorRepo; // COMENTADO
    // private DebtRepository $debtRepo;     // COMENTADO

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->orderItemRepo = new OrderItemRepository();
        // $this->debtorRepo = new DebtorRepository(); // COMENTADO
        // $this->debtRepo = new DebtRepository();     // COMENTADO
    }

    public function validate(array $data): array 
    {
        $errors = [];
        $items = $data['items'] ?? [];
        $paymentMethod = $data['payment_method'] ?? '';
        $debtorId = (int)($data['debtor_id'] ?? 0);
    
        if (empty($items)) $errors['items'] = 'O pedido deve conter pelo menos um lanche.';
        if ($paymentMethod === '') $errors['payment_method'] = 'Método de pagamento é obrigatório.';

        // Lógica de validação de Fiado COMENTADA
        /*
        if ($paymentMethod === 'fiado' && $debtorId === 0) {
            $errors['debtor_id'] = 'Se o pagamento for fiado, o Devedor é obrigatório.';
        }
        */

        foreach ($items as $item) {
            if ((int)($item['quantity'] ?? 0) <= 0) {
                $errors['items'] = 'A quantidade de todos os itens deve ser maior que zero.';
                break;
            }
        }

        return $errors;
    }

    public function make(array $data): Order 
    {
        $id = isset($data['id']) ? (int)$data['id'] : null;
        $userId = (int)($data['user_id'] ?? 1); 
        $status = $data['status'] ?? 'Pago';
        $debtorId = (int)($data['debtor_id'] ?? 0);
        
        $totalAmount = (float)($data['total_amount'] ?? 0.00); 

        return new Order(
            $id, 
            $userId, 
            $status, 
            $totalAmount,
            // $debtorId > 0 ? $debtorId : null // COMENTADO: Não vamos usar debtors agora
            null
        );
    }
    
    public function makeAndSave(array $data): int
    {
        $totalAmount = 0.00;
        $orderItemsData = [];
        $paymentMethod = $data['payment_method'] ?? 'dinheiro';

        // 1. Calcular o total do pedido
        foreach ($data['items'] as $item) {
            $subtotal = (float)$item['price_at_sale'] * (int)$item['quantity'];
            $totalAmount += $subtotal;
            $orderItemsData[] = [
                'product_id' => (int)$item['product_id'],
                'quantity' => (int)$item['quantity'],
                'price_at_sale' => (float)$item['price_at_sale']
            ];
        }
        
        $data['total_amount'] = $totalAmount;
        // Definir status como 'Pago' se não for fiado, para evitar erros.
        $data['status'] = ($paymentMethod === 'fiado' ? 'Pendente' : 'Pago');
        
        // 2. Criar o objeto Order
        $order = $this->make($data);
        
        // Iniciar Transação (Se o seu Database suportar)
        // Database::getConnection()->beginTransaction(); 
        
        try {
            // 3. Salvar o Pedido principal
            $orderId = $this->orderRepo->create($order);
            
            // 4. Salvar os Itens do Pedido (OrderItems)
            foreach ($orderItemsData as $itemData) {
                $orderItem = new OrderItem(
                    null, 
                    $orderId, 
                    $itemData['product_id'], 
                    $itemData['quantity'], 
                    $itemData['price_at_sale']
                );
                $this->orderItemRepo->create($orderItem);
            }
            
            // 5. Lógica de FIADO - COMENTADO
            /*
            if ($paymentMethod === 'fiado' && $order->debtor_id) {
                $this->handleFiadoTransaction($orderId, $order->debtor_id, $totalAmount);
            }
            */

            // Commitar Transação
            // Database::getConnection()->commit(); 
            
            return $orderId;
            
        } catch (\Exception $e) {
            // Rollback Transação
            // Database::getConnection()->rollBack();
            // throw $e; 
            return 0; 
        }
    }
    
    // Método handleFiadoTransaction COMENTADO
    /*
    private function handleFiadoTransaction(int $orderId, int $debtorId, float $amount): void
    {
        // 1. Criar o registro de Dívida (Debt)
        $debt = new Debt(null, $debtorId, $orderId, $amount, 0.00, 'Aberto', null, null);
        $this->debtRepo->create($debt);

        // 2. Atualizar o saldo do Devedor (Debtor)
        $this->debtorRepo->addToBalance($debtorId, $amount);
    }
    */
    
    // Método para reverter a venda (usado no delete do OrdersController)
    public function deleteOrderTransaction(int $orderId): bool
    {
        // Iniciar Transação
        // Database::getConnection()->beginTransaction(); 
        
        try {
            $order = $this->orderRepo->find($orderId);
            if (!$order) return false;
            
            // Lógica de verificação de Fiado COMENTADA
            /*
            $isFiado = $order['status'] === 'Pendente' && $order['debtor_id'];

            // 1. Se for fiado, reverter a dívida e o saldo do devedor
            if ($isFiado) {
                $debt = $this->debtRepo->findByOrderId($orderId);
                if ($debt) {
                    $this->debtorRepo->subtractFromBalance($order['debtor_id'], $order['total_amount']);
                    $this->debtRepo->delete((int)$debt['id']);
                }
            }
            */

            // 2. Apagar os Itens do Pedido (A chave estrangeira no DB deve fazer isso automaticamente, mas a chamada é boa prática)
            $this->orderItemRepo->deleteByOrderId($orderId);

            // 3. Apagar o Pedido principal
            $this->orderRepo->delete($orderId);

            // Commitar Transação
            // Database::getConnection()->commit();
            return true;
            
        } catch (\Exception $e) {
            // Rollback Transação
            // Database::getConnection()->rollBack();
            return false;
        }
    }
}