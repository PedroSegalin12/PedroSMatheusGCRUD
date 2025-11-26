<?php
namespace App\Services;

use App\Models\Moto;

class MotoService {
    /**
     * Valida os dados de entrada para a criação ou atualização de uma moto.
     * @param array $data Os dados a serem validados (modelo, ano, Montadora_id).
     * @return array Um array contendo erros de validação, se houver.
     */
    public function validate(array $data): array {
        $errors = [];
        $modelo = trim($data['modelo'] ?? '');
        $ano = $data['ano'] ?? '';
        $Montadora_id = $data['Montadora_id'] ?? '';

        // 1. Validação do Modelo
        if ($modelo === '') {
            $errors['modelo'] = 'O modelo é obrigatório.';
        }

        // 2. Validação do Ano
        if ($ano === '' || !is_numeric($ano)) {
            $errors['ano'] = 'O ano é obrigatório e deve ser um número.';
        }

        // 3. Validação da Montadora
        if ($Montadora_id === '') {
            $errors['Montadora_id'] = 'A montadora é obrigatória.';
        }

        return $errors;
    }

    /**
     * Cria (ou constrói) uma nova instância do modelo moto.
     * @param array $data Os dados validados da moto.
     * @return Moto A instância da moto.
     */
    public function make(array $data): Moto {
        $id = isset($data['id']) ? (int)$data['id'] : null;
        $modelo = trim($data['modelo'] ?? '');
        $ano = (int)($data['ano'] ?? 0);
        $Montadora_id = (int)($data['Montadora_id'] ?? 0);
        $disponivel = isset($data['disponivel']) ? (bool)$data['disponivel'] : true;

        return new Moto($id, $modelo, $ano, $Montadora_id, $disponivel);
    }
}
