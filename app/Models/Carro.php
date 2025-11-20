<?php

namespace App\Models;

class Carro
{
    public ?int $id;
    public string $titulo;
    public ?int $ano_publicacao;
    public ?string $genero;
    public bool $disponivel;
    public int $montadora_id;

    public function __construct(?int $id, string $titulo, ?int $ano_publicacao, ?string $genero, bool $disponivel, int $montadora_id)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->ano_publicacao = $ano_publicacao;
        $this->genero = $genero;
        $this->disponivel = $disponivel;
        $this->montadora_id = $montadora_id;
    }
}
