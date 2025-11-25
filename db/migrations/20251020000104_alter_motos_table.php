<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterMotosTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('motos');
        
        // Remove colunas antigas
        $table->removeColumn('nome_moto')
              ->removeColumn('data_nascimento')
              ->removeColumn('nacionalidade')
              ->save();
        
        // Adiciona novas colunas
        $table->addColumn('modelo', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('ano', 'integer', ['limit' => 4, 'null' => false])
              ->addColumn('Montadora_id', 'integer', ['signed' => false, 'null' => false])
              ->addColumn('disponivel', 'boolean', ['default' => true])
              ->addForeignKey('Montadora_id', 'Montadoras', 'id', ['delete'=> 'NO ACTION', 'update'=> 'NO ACTION'])
              ->save();
    }
}

