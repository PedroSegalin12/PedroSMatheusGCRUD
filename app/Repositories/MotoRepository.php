<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\moto;
use PDO;

class motoRepository
{
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM motos");
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare("SELECT * FROM motos ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM motos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(moto $moto): int
    {
        $stmt = Database::getConnection()->prepare("INSERT INTO motos (modelo, ano, Montadora_id, disponivel) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $moto->modelo,
            $moto->ano,
            $moto->Montadora_id,
            $moto->disponivel ? 1 : 0
        ]);
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(moto $moto): bool
    {
        $stmt = Database::getConnection()->prepare("UPDATE motos SET modelo = ?, ano = ?, Montadora_id = ?, disponivel = ? WHERE id = ?");
        return $stmt->execute([
            $moto->modelo,
            $moto->ano,
            $moto->Montadora_id,
            $moto->disponivel ? 1 : 0,
            $moto->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM motos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM motos ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArray(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM motos ORDER BY id DESC");
        $stmt->execute();
        $motos = $stmt->fetchAll();
        $return = [];
        foreach ($motos as $moto) {
            $return[$moto['id']] = $moto['modelo'];
        }
        return $return;
    }
}