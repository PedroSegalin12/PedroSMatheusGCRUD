<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMontadorasTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('Montadoras')
            ->addColumn('nome', 'string', ['limit' => 100])
            ->addColumn('cidade', 'string', ['limit' => 100])
            ->addColumn('telefone', 'string', ['limit' => 20])
            ->create();
    }
}
