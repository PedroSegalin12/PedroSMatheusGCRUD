<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\montadora;
use PDO;

class montadoraRepository
{
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM montadoras");
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare("SELECT * FROM montadoras ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM montadoras WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(montadora $montadora): int
    {
        $stmt = Database::getConnection()->prepare("INSERT INTO montadoras (nome, cidade, telefone) VALUES (?, ?, ?)");
        $stmt->execute([$montadora->nome, $montadora->cidade, $montadora->telefone]);
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(montadora $montadora): bool
    {
        $stmt = Database::getConnection()->prepare("UPDATE montadoras SET nome = ?, cidade = ?, telefone = ? WHERE id = ?");
        return $stmt->execute([$montadora->nome, $montadora->cidade, $montadora->telefone, $montadora->id]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM montadoras WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM montadoras ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getArray(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM montadoras ORDER BY id DESC");
        $stmt->execute();
        $montadoras = $stmt->fetchAll();
        $return = [];
        foreach ($montadoras as $montadora) {
            $return[$montadora['id']] = $montadora['nome'];
        }
        return $return;
    }
}
