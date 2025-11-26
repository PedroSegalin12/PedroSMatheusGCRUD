<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Testdrive;
use PDO;

class TestdriveRepository
{
    private string $table = 'testdrives'; // tabela correta

    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM {$this->table}");
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare(
            "SELECT * FROM {$this->table} ORDER BY id DESC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Testdrive::class);
    }

    public function find(int $id): ?Testdrive
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetchObject(Testdrive::class);
        return $row instanceof Testdrive ? $row : null;
    }

    public function create(Testdrive $testdrive): int
    {
        $sql = "INSERT INTO {$this->table} (id_user, id_carro, data_testdrive, data_devolucao, status) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            $testdrive->id_user,
            $testdrive->id_carro,
            $testdrive->data_testdrive,
            $testdrive->data_devolucao,
            $testdrive->status
        ]);
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(Testdrive $testdrive): bool
    {
        $sql = "UPDATE {$this->table} 
                SET id_user = ?, id_carro = ?, data_testdrive = ?, data_devolucao = ?, status = ? 
                WHERE id = ?";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute([
            $testdrive->id_user,
            $testdrive->id_carro,
            $testdrive->data_testdrive,
            $testdrive->data_devolucao,
            $testdrive->status,
            $testdrive->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Testdrive::class);
    }

    public function getArray(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        $testdrives = $stmt->fetchAll();
        $return = [];
        foreach ($testdrives as $td) {
            $return[$td['id']] = "Carro #{$td['id_carro']} para User #{$td['id_user']} ({$td['status']})";
        }
        return $return;
    }
}
