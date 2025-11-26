<?php

namespace App\Services;

use App\Models\carro;

class carroService {
    public function validate(array $data): array {
        $errors = [];
        $titulo = trim($data['titulo'] ?? '');
        $ano_publicacao = $data['ano_publicacao'] ?? '';
        $Montadora_id = $data['Montadora_id'] ?? '';
        $genero = trim($data['genero'] ?? '');

        if ($titulo === '') $errors['titulo'] = 'Título é obrigatório';
        if ($ano_publicacao !== '' && !is_numeric($ano_publicacao)) $errors['ano_publicacao'] = 'O ano deve ser um número';
        if ($Montadora_id === '') $errors['Montadora_id'] = 'Montadora é obrigatória';

        return $errors;
    }

    public function make(array $data): carro {
        $id = isset($data['id']) ? (int)$data['id'] : null;
        $titulo = trim($data['titulo'] ?? '');
        $ano_publicacao = ($data['ano_publicacao'] ?? '') !== '' ? (int)$data['ano_publicacao'] : null;
        $genero = trim($data['genero'] ?? '') !== '' ? trim($data['genero']) : null;
        $disponivel = isset($data['disponivel']) ? (bool)$data['disponivel'] : true;
        $Montadora_id = (int)($data['Montadora_id'] ?? 0);

        return new carro($id, $titulo, $ano_publicacao, $genero, $disponivel, $Montadora_id);
    }
}
