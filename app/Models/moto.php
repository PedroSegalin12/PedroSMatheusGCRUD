<?php

namespace App\Models;

class moto
{
    public ?int $id;
    public string $nome_moto;
    public string $data_nascimento;

    public string $nacionalidade;

    public function __construct(?int $id, string $nome_moto, string $data_nascimento, string $nacionalidade)
    {
        $this->id = $id;
        $this->nome_moto = $nome_moto;
        $this->data_nascimento = $data_nascimento;
        $this->nacionalidade = $nacionalidade;
    }
}