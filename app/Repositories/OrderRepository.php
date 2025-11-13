<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class OrderRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM orders ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
