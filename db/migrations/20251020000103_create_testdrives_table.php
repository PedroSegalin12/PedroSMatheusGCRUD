<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTestdrivesTable extends AbstractMigration
{
    public function change(): void
    {
       $this->table('testdrives', ['engine' => 'InnoDB'])
            ->addColumn('user_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('carro_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('data_testdrive', 'date', ['null' => false])
            ->addColumn('data_devolucao', 'date', ['null' => true])
            ->addColumn('status', 'enum', [
                'values' => ['emprestado', 'devolvido'],
                'default' => 'emprestado',
                'null' => false
            ])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'CASCADE'])
            ->addForeignKey('carro_id', 'carros', 'id', ['delete'=> 'RESTRICT', 'update'=> 'CASCADE'])
            ->create();
    }
}
