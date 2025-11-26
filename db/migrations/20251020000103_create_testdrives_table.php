<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTestdrivesTable extends AbstractMigration
{
    public function change(): void
    {
       $this->table('testdrives', ['engine' => 'InnoDB'])
            ->addColumn('id_user', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('id_carro', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('data_testdrive', 'date', ['null' => false])
            ->addColumn('data_devolucao', 'date', ['null' => true])
            ->addColumn('status', 'enum', [
                'values' => ['pendente', 'finalizado', 'testdrive', 'devolvido'],
                'default' => 'pendente',
                'null' => false
            ])
            ->addForeignKey('id_user', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'CASCADE'])
            ->addForeignKey('id_carro', 'carros', 'id', ['delete'=> 'RESTRICT', 'update'=> 'CASCADE'])
            ->create();
    }
}
