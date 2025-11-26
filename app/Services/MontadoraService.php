<?php
namespace App\Services;

use App\Models\Montadora;

class MontadoraService {
    public function validate(array $data): array {
        $errors = [];
        $nome = trim($data['nome'] ?? '');
        $cidade = trim($data['cidade'] ?? '');
        $telefone = trim($data['telefone'] ?? '');

        if ($nome === '') $errors['nome'] = 'Nome é obrigatório';

        if ($cidade === '') $errors['cidade'] = 'Cidade é obrigatória';

        if ($telefone === '') $errors['telefone'] = 'Telefone é obrigatório';

        return $errors;
    }

    public function make(array $data): Montadora {
        $nome = trim($data['nome'] ?? '');
        $cidade = trim($data['cidade'] ?? '');
        $telefone = trim($data['telefone'] ?? '');
        $id = isset($data['id']) ? (int)$data['id'] : null;
        return new Montadora($id, $nome, $cidade, $telefone);
    }
}

//Loucura