<?php

namespace App\Services;

use App\Models\testdrive;
use \DateTime; // Importa a classe DateTime do PHP

class testdriveervice
{
    /**
     * Valida os dados de entrada para a criação ou atualização de um Empréstimo.
     * @param array $data Os dados a serem validados.
     * @return array Um array contendo erros de validação, se houver.
     */
    public function validate(array $data): array
    {
        $errors = [];
        // Converte para inteiros e strings para validação
        $id_user = filter_var($data['id_user'] ?? '', FILTER_VALIDATE_INT);
        $id_carro = filter_var($data['id_carro'] ?? '', FILTER_VALIDATE_INT);
        
        $data_testdrive = trim($data['data_testdrive'] ?? '');
        $data_devolucao = trim($data['data_devolucao'] ?? ''); // Pode ser vazio
        $status = trim($data['status'] ?? '');
        
        $valid_statuses = ['testdrive', 'devolvido'];

        // 1. Validação do ID do Usuário (id_user)
        if ($id_user === false || $id_user <= 0) {
            $errors['id_user'] = 'ID do Usuário é obrigatório e deve ser um número inteiro válido.';
        }

        // 2. Validação do ID do carro (id_carro)
        if ($id_carro === false || $id_carro <= 0) {
            $errors['id_carro'] = 'ID do carro é obrigatório e deve ser um número inteiro válido.';
        }

        // 3. Validação do Status
        if (!in_array($status, $valid_statuses, true)) {
            $errors['status'] = 'Status inválido. Deve ser "testdrive" ou "devolvido".';
        }

        // 4. Validação da Data de Empréstimo (Obrigatória)
        if ($data_testdrive === '') {
            $errors['data_testdrive'] = 'A Data de Empréstimo é obrigatória.';
        } else {
            $datetestdrive = DateTime::createFromFormat('Y-m-d', $data_testdrive);
            
            if (!$datetestdrive || $datetestdrive->format('Y-m-d') !== $data_testdrive) {
                $errors['data_testdrive'] = 'Formato de data de empréstimo inválido. Use YYYY-MM-DD.';
            } else {
                // Se a data de empréstimo for futura, geralmente é um erro
                $today = new DateTime();
                if ($datetestdrive > $today) {
                    $errors['data_testdrive'] = 'A data de empréstimo não pode ser futura.';
                }
            }
        }

        // 5. Validação da Data de Devolução (Opcional)
        if ($data_devolucao !== '') {
            $dateDevolucao = DateTime::createFromFormat('Y-m-d', $data_devolucao);
            
            if (!$dateDevolucao || $dateDevolucao->format('Y-m-d') !== $data_devolucao) {
                $errors['data_devolucao'] = 'Formato de data de devolução inválido. Use YYYY-MM-DD.';
            } 
            
            // Se ambas as datas são válidas, verifica se a devolução é depois do empréstimo
            if (!isset($errors['data_testdrive']) && !isset($errors['data_devolucao'])) {
                if ($dateDevolucao < $datetestdrive) {
                    $errors['data_devolucao'] = 'A data de devolução não pode ser anterior à data de empréstimo.';
                }
            }
        }


        return $errors;
    }

    /**
     * Cria (ou constrói) uma nova instância do modelo testdrive.
     * @param array $data Os dados validados.
     * @return testdrive A instância do testdrive.
     */
    public function make(array $data): testdrive
    {
        // Limpeza e conversão dos dados
        $id = isset($data['id']) ? (int)$data['id'] : null; // Chave primária
        $id_user = (int)($data['id_user'] ?? 0);
        $id_carro = (int)($data['id_carro'] ?? 0);
        $data_testdrive = trim($data['data_testdrive'] ?? '');
        $data_devolucao = trim($data['data_devolucao'] ?? '');
        $status = trim($data['status'] ?? 'testdrive'); // Default para 'testdrive'
        
        // Se a data de devolução estiver vazia, passamos null (conforme o Model testdrive)
        $data_devolucao_model = $data_devolucao !== '' ? $data_devolucao : null;

        // Note que o construtor do testdrive que definimos é:
        // __construct(?int $id, int $id_user, int $id_carro, string $data_testdrive, ?string $data_devolucao, string $status)
        return new testdrive(
            $id,
            $id_user,
            $id_carro,
            $data_testdrive,
            $data_devolucao_model,
            $status
        );
    }
}