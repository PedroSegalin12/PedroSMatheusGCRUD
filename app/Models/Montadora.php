<?php

namespace App\Models;

class Montadora
{
    public ?int $id;
    public string $nome;
    public string $cidade;
    public string $telefone;

    public function __construct(?int $id, string $nome, string $cidade, string $telefone)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->cidade = $cidade;
        $this->telefone = $telefone;
    }
}
