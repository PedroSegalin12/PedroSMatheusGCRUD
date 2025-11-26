<?php

namespace App\Models;

class testdrive
{
    public ?int $id = null;
    public ?int $id_user = null;
    public ?int $id_carro = null;
    public ?string $data_testdrive = null;
    public ?string $data_devolucao = null;
    public string $status = 'pendente';

    public function __construct()
    {
    }
}
?>