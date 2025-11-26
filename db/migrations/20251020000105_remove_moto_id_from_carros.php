<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RemoveMotoIdFromCarros extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('carros');
        
        // Tenta remover a foreign key primeiro (pode não existir se já foi removida ou se a tabela foi criada sem ela)
        try {
            $table->dropForeignKey('moto_id');
        } catch (\Exception $e) {
            // Ignora se a foreign key não existir
        }
        
        // Tenta remover a coluna moto_id (pode não existir se a tabela foi criada sem ela)
        try {
            $table->removeColumn('moto_id')
                  ->save();
        } catch (\Exception $e) {
            // Ignora se a coluna não existir (tabela já foi criada sem ela)
        }
    }
}

