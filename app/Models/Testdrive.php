<?php

namespace App\Models;

class testdrive
{
    public ?int $id;
    public int $id_user;
    public int $id_carro;
    public string $data_testdrive;
    public ?string $data_devolucao;
    public string $status;

    public function __construct(
        ?int $id,
        int $id_user,
        int $id_carro,
        string $data_testdrive,
        ?string $data_devolucao = null,
        string $status = 'pendente'
    ) {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->id_carro = $id_carro;
        $this->data_testdrive = $data_testdrive;
        $this->data_devolucao = $data_devolucao;
        $this->status = $status;
    }
}
