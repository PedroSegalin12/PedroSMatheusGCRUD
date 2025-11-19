<?php
namespace App\Services;

use App\Models\carro;

class carroservice {
    public function validate(array $data): array {
        $errors = [];
        $titulo = trim($data['titulo'] ?? '');
        $ano_publicacao = $data['ano_publicacao'] ?? '';
        $editora_id = $data['editora_id'] ?? '';
        $genero = trim($data['genero'] ?? '');
        $autor_id = $data['autor_id'] ?? '';

        if ($titulo === '') $errors['titulo'] = 'Título é obrigatório';
        if (!is_numeric($ano_publicacao) || ($ano_publicacao === ''))  $errors['ano_publicacao'] = 'O ano deve ser um número';
        if ($editora_id === '') $errors['editora_id'] = 'Editora é obrigatória';
        if ($autor_id === '') $errors['autor_id'] = 'Autor é obrigatório';
        if ($genero === '') $errors['genero'] = 'Gênero é obrigatório';


        return $errors;
    }


    public function make(array $data): carro {
        $id = isset($data['id']) ? (int)$data['id'] : null;
        $titulo = trim($data['titulo'] ?? '');
        $ano_publicacao = (int)($data['ano_publicacao'] ?? 0);
        $editora_id = (int)($data['editora_id'] ?? 0);
        $autor_id = (int)($data['autor_id'] ?? 0);
        $genero = trim($data['genero'] ?? '');
        $disponivel = (bool)($data['disponivel'] ?? true);
        return new carro($id, $titulo, $ano_publicacao, $genero, $disponivel, $editora_id, $autor_id);
    }
}
