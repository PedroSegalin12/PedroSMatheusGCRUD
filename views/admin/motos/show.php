<?php $this->layout('layouts/admin', ['title' => 'Detalhes da Moto']) ?>

<?php $this->start('body') ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detalhes da Moto</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label class="form-label"><strong>ID:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($moto['id']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Modelo:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($moto['modelo']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Ano:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($moto['ano']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Montadora:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($moto['Montadora_nome'] ?? 'Desconhecida') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Disponível:</strong></label>
                    <input type="text" class="form-control" value="<?= $moto['disponivel'] ? 'Sim' : 'Não' ?>" readonly>
                </div>
                <div class="text-end">
                    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->stop() ?>