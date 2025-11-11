<?php

namespace App\Models;

class Order
{
    public ?int $id;
    public int $user_id;             // ID do usuário que registrou a venda (FK para users)
    public string $status;           // Ex: 'Pendente', 'Pago', 'Entregue'
    public float $total_amount;      // Valor total da venda
    public ?int $debtor_id;          // ID do devedor (se for fiado) - FK para debtors
    public ?string $created_at;      // Data/Hora da criação
    public ?string $updated_at;      // Data/Hora da atualização

    public function __construct(
        ?int $id, 
        int $user_id, 
        string $status, 
        float $total_amount,
        ?int $debtor_id = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->status = $status;
        $this->total_amount = $total_amount;
        $this->debtor_id = $debtor_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}