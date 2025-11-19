<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\carro;
use PDO;

class carroRepository {
    public function countAll(): int {
         $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM carros");
        return (int)$stmt->fetchColumn();
    }
    public function paginate(int $page, int $perPage): array {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare("SELECT * FROM carros ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function find(int $id): ?array {
        $stmt = Database::getConnection()->prepare("SELECT * FROM carros WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
    public function create(carro $p): int {
        $stmt = Database::getConnection()->prepare("INSERT INTO carros (editora_id, titulo, ano_publicacao, genero, disponivel) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$p->editora_id, $p->titulo, $p->ano_publicacao, $p->genero, $p->disponivel]);
        return (int)Database::getConnection()->lastInsertId();
    }
    public function update(carro $p): bool {
        $stmt = Database::getConnection()->prepare("UPDATE carros SET editora_id = ?, titulo = ?, ano_publicacao = ?, genero = ?, disponivel = ? WHERE id = ?");
        return $stmt->execute([$p->editora_id, $p->titulo, $p->ano_publicacao, $p->genero, $p->disponivel, $p->id]);
    }
    public function delete(int $id): bool {
        $stmt = Database::getConnection()->prepare("DELETE FROM carros WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function findByEditoraId(int $id): ?array {
        $stmt = Database::getConnection()->prepare("SELECT * FROM carros WHERE editora_id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: [];
    }
}
