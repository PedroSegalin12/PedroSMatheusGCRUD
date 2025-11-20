<?php
namespace App\Services;

use App\Models\moto;
use \DateTime; // Importa a classe DateTime do PHP

class motoService {
    /**
     * Valida os dados de entrada para a criação ou atualização de um moto.
     * @param array $data Os dados a serem validados (nome, data de nascimento, nacionalidade).
     * @return array Um array contendo erros de validação, se houver.
     */
    public function validate(array $data): array {
        $errors = [];
        $nome_moto = trim($data['nome_moto'] ?? '');
        $data_nascimento = trim($data['data_nascimento'] ?? '');
        $nacionalidade = trim($data['nacionalidade'] ?? '');

        // 1. Validação do Nome do moto
        if ($nome_moto === '') {
            $errors['nome_moto'] = 'O nome do moto é obrigatório.';
        }

        // 2. Validação da Nacionalidade
        if ($nacionalidade === '') {
            $errors['nacionalidade'] = 'A nacionalidade é obrigatória.';
        }

        // 3. Validação da Data de Nascimento
        if ($data_nascimento === '') {
            $errors['data_nascimento'] = 'A data de nascimento é obrigatória.';
        } else {
            // Tenta criar um objeto DateTime usando o formato esperado (YYYY-MM-DD)
            $dateObject = DateTime::createFromFormat('Y-m-d', $data_nascimento);
            
            // Verifica se a data é válida E se a string original corresponde exatamente ao formato
            // O operador '!' antes de $dateObject verifica se a criação falhou (data inválida)
            if (!$dateObject || $dateObject->format('Y-m-d') !== $data_nascimento) {
                $errors['data_nascimento'] = 'Formato de data inválido. Use YYYY-MM-DD.';
            } else {
                // Verifica se a data de nascimento não é futura
                $today = new DateTime();
                if ($dateObject > $today) {
                    $errors['data_nascimento'] = 'A data de nascimento não pode ser futura.';
                }
            }
        }

        return $errors;
    }

    /**
     * Cria (ou constrói) uma nova instância do modelo moto.
     * @param array $data Os dados validados do moto.
     * @return moto A instância do moto.
     */
    public function make(array $data): moto {
        $nome_moto = trim($data['nome_moto'] ?? '');
        $data_nascimento = trim($data['data_nascimento'] ?? '');
        $nacionalidade = trim($data['nacionalidade'] ?? '');
        
        // Assume que 'id_moto' é usado para atualização, e 'id' na sua versão anterior.
        // Mantenho 'id' para compatibilidade com sua estrutura anterior.
        $id_moto = isset($data['id_moto']) ? (int)$data['id_moto'] : null;

        // Se o seu construtor espera o ID, Nome, Data de Nascimento e Nacionalidade:
        return new moto($id_moto, $nome_moto, $data_nascimento, $nacionalidade);
    }
}