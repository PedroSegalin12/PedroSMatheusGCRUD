<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Order;
use PDO;

class OrderRepository
{
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM orders");
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        // SELECT ajustado para trazer o nome do usuário/devedor se necessário na listagem.
        // Por enquanto, apenas seleciona da tabela orders.
        $stmt = Database::getConnection()->prepare("SELECT * FROM orders ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(Order $order): int
    {
        $sql = "INSERT INTO orders (user_id, status, total_amount, debtor_id) VALUES (?, ?, ?, ?)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            $order->user_id, 
            $order->status, 
            $order->total_amount, 
            $order->debtor_id
        ]);
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(Order $order): bool
    {
        $sql = "UPDATE orders SET user_id = ?, status = ?, total_amount = ?, debtor_id = ? WHERE id = ?";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute([
            $order->user_id, 
            $order->status, 
            $order->total_amount, 
            $order->debtor_id,
            $order->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM orders WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM orders ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}