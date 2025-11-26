<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMotosTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('motos')
            ->addColumn('modelo', 'string', ['limit' => 255])
            ->addColumn('ano', 'integer', ['limit' => 4])
            ->addColumn('Montadora_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('disponivel', 'boolean', ['default' => true])
            ->addForeignKey('Montadora_id', 'Montadoras', 'id', ['delete'=> 'NO ACTION', 'update'=> 'NO ACTION'])
            ->create();
    }
}
?>