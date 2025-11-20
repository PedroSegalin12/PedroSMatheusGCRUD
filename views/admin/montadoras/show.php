<?php $this->layout('layouts/admin', ['title' => 'Detalhe da montadora']) ?>

<?php $this->start('body') ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detalhes da montadora</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label class="form-label"><strong>ID:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($montadora['id']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Nome:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($montadora['nome']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Cidade:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($montadora['cidade']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Telefone:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($montadora['telefone']) ?>" readonly>
                </div>
                <div class="text-end">
                    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->stop() ?>
