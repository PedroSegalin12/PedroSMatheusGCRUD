<?php

namespace App\Models;

class Moto
{
    public ?int $id;
    public string $modelo;
    public int $ano;
    public int $Montadora_id;
    public bool $disponivel;

    public function __construct(?int $id, string $modelo, int $ano, int $Montadora_id, bool $disponivel)
    {
        $this->id = $id;
        $this->modelo = $modelo;
        $this->ano = $ano;
        $this->Montadora_id = $Montadora_id;
        $this->disponivel = $disponivel;
    }
}