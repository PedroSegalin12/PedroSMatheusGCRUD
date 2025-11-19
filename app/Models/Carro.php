<?php

namespace App\Models;

class carro
{
    public ?int $id;
    public string $titulo;
    public ?int $ano_publicacao;
    public ?string $genero;
    public bool $disponivel;
    public int $editora_id;
    public int $autor_id;

    public function __construct(?int $id, string $titulo, ?int $ano_publicacao, ?string $genero, bool $disponivel, int $editora_id, int $autor_id)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->ano_publicacao = $ano_publicacao;
        $this->editora_id = $editora_id;
        $this->genero = $genero;
        $this->disponivel = $disponivel;
        $this->autor_id = $autor_id;
    }
}
