<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMotosTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('motos')
            ->addColumn('nome_moto', 'string', ['limit' => 255])
            ->addColumn('data_nascimento', 'date')
            ->addColumn('nacionalidade', 'string', ['limit' => 50])
            ->create();
    }
}
