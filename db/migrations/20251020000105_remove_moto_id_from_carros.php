<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RemoveMotoIdFromCarros extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('carros');
        
        // Remove a foreign key primeiro
        $table->dropForeignKey('moto_id');
        
        // Remove a coluna moto_id
        $table->removeColumn('moto_id')
              ->save();
    }
}

