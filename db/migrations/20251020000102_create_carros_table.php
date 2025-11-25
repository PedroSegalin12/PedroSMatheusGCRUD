<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCarrosTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('carros', ['engine' => 'InnoDB'])
            ->addColumn('Montadora_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('moto_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('titulo', 'string', ['limit' => 150, 'null' => false])
            ->addColumn('ano_publicacao', 'integer', ['limit' => 4, 'null' => true])
            ->addColumn('genero', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('disponivel', 'boolean', ['default' => true])
            ->addForeignKey('Montadora_id', 'Montadoras', 'id', ['delete'=> 'NO ACTION', 'update'=> 'NO ACTION'])
            ->addForeignKey('moto_id', 'motos', 'id', ['delete'=> 'NO ACTION', 'update'=> 'NO ACTION'])
            ->create();
    }
}
