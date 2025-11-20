<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Carro;
use PDO;

class CarroRepository
{
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM carros");
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare("SELECT * FROM carros ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM carros WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(Carro $carro): int
    {
        $stmt = Database::getConnection()->prepare(
            "INSERT INTO carros (titulo, ano_publicacao, genero, disponivel, montadora_id) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $carro->titulo,
            $carro->ano_publicacao,
            $carro->genero,
            $carro->disponivel ? 1 : 0,
            $carro->montadora_id
        ]);
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(Carro $carro): bool
    {
        $stmt = Database::getConnection()->prepare(
            "UPDATE carros SET titulo = ?, ano_publicacao = ?, genero = ?, disponivel = ?, montadora_id = ? WHERE id = ?"
        );
        return $stmt->execute([
            $carro->titulo,
            $carro->ano_publicacao,
            $carro->genero,
            $carro->disponivel ? 1 : 0,
            $carro->montadora_id,
            $carro->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM carros WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
