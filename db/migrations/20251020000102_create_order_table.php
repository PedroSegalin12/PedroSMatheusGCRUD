<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrderTable extends AbstractMigration 
{
    public function change(): void
    {
        // sempre defina o engine como InnoDB
        $table = $this->table('orders', ['engine' => 'InnoDB']); 
        
        $table
            ->addColumn('user_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'CASCADE'])
            ->addColumn('debtor_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('status', 'enum', [
                'values' => ['Pendente', 'Pago', 'Entregue', 'Cancelado'], 
                'default' => 'Pendente'
            ])
            ->addColumn('total_amount', 'decimal', [
                'precision' => 10, 
                'scale' => 2, 
                'default' => 0.00
            ])
            ->addTimestamps()
            ->create();
    }
}
