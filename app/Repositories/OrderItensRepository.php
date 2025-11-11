<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\OrderItem;
use PDO;

class OrderItemRepository
{
    /**
     * Busca todos os itens de um pedido específico.
     * @param int $orderId O ID do pedido.
     * @return array
     */
    public function findByOrderId(int $orderId): array
    {
        // Se você precisar, pode juntar com a tabela 'products' para pegar o nome
        $stmt = Database::getConnection()->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cria um novo item de pedido (lanche vendido).
     * Este é o método usado pelo OrderService::makeAndSave.
     * @param OrderItem $orderItem O objeto OrderItem a ser salvo.
     * @return int O ID do item inserido.
     */
    public function create(OrderItem $orderItem): int
    {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price_at_sale) VALUES (?, ?, ?, ?)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            $orderItem->order_id,
            $orderItem->product_id,
            $orderItem->quantity,
            $orderItem->price_at_sale
        ]);
        return (int)Database::getConnection()->lastInsertId();
    }

    /**
     * Apaga TODOS os itens de um determinado pedido.
     * Este é o método usado pelo OrderService::deleteOrderTransaction.
     * @param int $orderId O ID do pedido cujos itens serão apagados.
     * @return bool
     */
    public function deleteByOrderId(int $orderId): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM order_items WHERE order_id = ?");
        return $stmt->execute([$orderId]);
    }
}