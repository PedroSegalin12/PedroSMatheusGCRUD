<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Montadora;
use PDO;

class MontadoraRepository
{
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM Montadoras");
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare("SELECT * FROM Montadoras ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM Montadoras WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(Montadora $Montadora): int
    {
        $stmt = Database::getConnection()->prepare("INSERT INTO Montadoras (nome, cidade, telefone) VALUES (?, ?, ?)");
        $stmt->execute([$Montadora->nome, $Montadora->cidade, $Montadora->telefone]);
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(Montadora $Montadora): bool
    {
        $stmt = Database::getConnection()->prepare("UPDATE Montadoras SET nome = ?, cidade = ?, telefone = ? WHERE id = ?");
        return $stmt->execute([$Montadora->nome, $Montadora->cidade, $Montadora->telefone, $Montadora->id]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM Montadoras WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM Montadoras ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getArray(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM Montadoras ORDER BY id DESC");
        $stmt->execute();
        $Montadoras = $stmt->fetchAll();
        $return = [];
        foreach ($Montadoras as $Montadora) {
            $return[$Montadora['id']] = $Montadora['nome'];
        }
        return $return;
    }
}
