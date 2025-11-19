<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\testdrive; // Importa o Model testdrive
use PDO;

class testdriveRepository
{
    // Conta todos os registros na tabela testdrive
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM testdrive");
        return (int)$stmt->fetchColumn();
    }

    // Retorna uma página de empréstimos
    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        // Ordena pela nova chave primária 'id'
        $stmt = Database::getConnection()->prepare("SELECT * FROM testdrive ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, testdrive::class); // Retorna como objetos testdrive
    }

    // Busca um empréstimo pelo seu ID
    public function find(int $id): ?testdrive
    {
        // Alterado 'id_testdrive' para 'id'
        $stmt = Database::getConnection()->prepare("SELECT * FROM testdrive WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetchObject(testdrive::class);
        return $row instanceof testdrive ? $row : null;
    }

    // Cria um novo empréstimo no banco de dados
    public function create(testdrive $testdrive): int
    {
        $sql = "INSERT INTO testdrive (id_user, id_carro, data_testdrive, data_devolucao, status) 
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

    // Atualiza um empréstimo existente
    public function update(testdrive $testdrive): bool
    {
        $sql = "UPDATE testdrive SET id_user = ?, id_carro = ?, data_testdrive = ?, data_devolucao = ?, status = ? 
                WHERE id = ?";
        $stmt = Database::getConnection()->prepare($sql);
        
        // Agora acessa $testdrive->id (Assumindo que o Model testdrive também foi atualizado)
        return $stmt->execute([
            $testdrive->id_user, 
            $testdrive->id_carro, 
            $testdrive->data_testdrive, 
            $testdrive->data_devolucao, 
            $testdrive->status,
            $testdrive->id // Chave primária para o WHERE
        ]);
    }

    // Deleta um empréstimo pelo ID
    public function delete(int $id): bool
    {
        // Alterado 'id_testdrive' para 'id'
        $stmt = Database::getConnection()->prepare("DELETE FROM testdrive WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Retorna todos os empréstimos como objetos testdrive
    public function findAll(): array
    {
        // Alterado 'id_testdrive' para 'id'
        $stmt = Database::getConnection()->prepare("SELECT * FROM testdrive ORDER BY id DESC");
        $stmt->execute();
        // Retorna um array de objetos testdrive
        return $stmt->fetchAll(PDO::FETCH_CLASS, testdrive::class);
    }

    // Retorna um array associativo (id => Descrição do Empréstimo)
    public function getArray(): array
    {
        // Alterado 'id_testdrive' para 'id'
        $stmt = Database::getConnection()->prepare("SELECT * FROM testdrive ORDER BY id DESC");
        $stmt->execute();
        $testdrive = $stmt->fetchAll();
        $return = [];
        foreach ($testdrive as $testdrive) {
            // Mapeia o ID do empréstimo (agora 'id') para uma descrição relevante
            $return[$testdrive['id']] = "carro #{$testdrive['id_carro']} para User #{$testdrive['id_user']} ({$testdrive['status']})";
        }
        return $return;
    }
}