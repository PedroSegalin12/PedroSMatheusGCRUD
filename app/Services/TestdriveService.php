<?php

namespace App\Services;

use App\Models\Testdrive;
use DateTime;

class TestdriveService
{
    public function validate(array $data): array
    {
        $errors = [];

        $id_user = filter_var($data['id_user'] ?? '', FILTER_VALIDATE_INT);
        $id_carro = filter_var($data['id_carro'] ?? '', FILTER_VALIDATE_INT);
        $data_testdrive = trim($data['data_testdrive'] ?? '');
        $data_devolucao = trim($data['data_devolucao'] ?? '');
        $status = trim($data['status'] ?? 'pendente');
        $valid_statuses = ['pendente', 'finalizado', 'testdrive', 'devolvido'];

        if ($id_user === false || $id_user <= 0) {
            $errors['id_user'] = 'ID do Usuário é obrigatório e deve ser um número inteiro válido.';
        }

        if ($id_carro === false || $id_carro <= 0) {
            $errors['id_carro'] = 'ID do carro é obrigatório e deve ser um número inteiro válido.';
        }

        if (!in_array($status, $valid_statuses, true)) {
            $errors['status'] = 'Status inválido. Deve ser "pendente", "finalizado", "testdrive" ou "devolvido".';
        }

        if ($data_testdrive === '') {
            $errors['data_testdrive'] = 'A Data do testdrive é obrigatória.';
        } else {
            $dt = DateTime::createFromFormat('Y-m-d', $data_testdrive);
            if (!$dt || $dt->format('Y-m-d') !== $data_testdrive) {
                $errors['data_testdrive'] = 'Formato de data inválido. Use YYYY-MM-DD.';
            }
        }

        if ($data_devolucao !== '') {
            $dd = DateTime::createFromFormat('Y-m-d', $data_devolucao);
            if (!$dd || $dd->format('Y-m-d') !== $data_devolucao) {
                $errors['data_devolucao'] = 'Formato de data de devolução inválido. Use YYYY-MM-DD.';
            }
        }

        return $errors;
    }

    public function make(array $data): Testdrive
{
    $t = new Testdrive();

    $t->id = $data['id'] ?? null;
    $t->id_user = (int)($data['id_user'] ?? 0);
    $t->id_carro = (int)($data['id_carro'] ?? 0);
    $t->data_testdrive = $data['data_testdrive'] ?? null;
    $t->data_devolucao = $data['data_devolucao'] ?? null;
    $t->status = $data['status'] ?? 'pendente';

    return $t;
}
}

?>