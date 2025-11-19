<?php

namespace App\Models;

class test-drive
{
    // Corresponde a id_test-drives INT AUTO_INCREMENT PRIMARY KEY
    public ?int $id; 
    
    // Corresponde a id_user INT NOT NULL (Chave Estrangeira)
    public int $id_user;
    
    // Corresponde a id_carro INT NOT NULL (Chave Estrangeira)
    public int $id_carro;
    
    // Corresponde a data_test-drive DATE NOT NULL
    public string $data_test-drive;
    
    // Corresponde a data_devolucao DATE
    public ?string $data_devolucao;
    
    // Corresponde a status ENUM('emprestado', 'devolvido') DEFAULT 'emprestado'
    // Embora seja um ENUM no banco, no PHP é geralmente representada como uma string
    public string $status; 

    public function __construct(
        ?int $id, 
        int $id_user, 
        int $id_carro, 
        string $data_test-drive, 
        ?string $data_devolucao, 
        string $status
    ) {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->id_carro = $id_carro;
        $this->data_test-drive = $data_test-drive;
        
        // data_devolucao é nullable no banco, por isso o tipo ?string no construtor e na propriedade
        $this->data_devolucao = $data_devolucao; 
        
        $this->status = $status;
    }
}
