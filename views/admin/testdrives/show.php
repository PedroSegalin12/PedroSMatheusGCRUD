<?php $this->layout('layouts/admin', ['title' => 'Detalhes do Testdrive']) ?>

<?php $this->start('body') ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detalhes do Testdrive</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label class="form-label"><strong>ID:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($testdrive->id ?? $testdrive['id'] ?? '') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>ID do Usuário:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($testdrive->id_user ?? $testdrive['id_user'] ?? '') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>ID do Carro:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($testdrive->id_carro ?? $testdrive['id_carro'] ?? '') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Data do Testdrive:</strong></label>
                    <input type="date" class="form-control"
                        value="<?= $this->e($testdrive->data_testdrive ?? $testdrive['data_testdrive'] ?? '') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Data de Devolução:</strong></label>
                    <input type="date" class="form-control" value="<?= $this->e($testdrive->data_devolucao ?? $testdrive['data_devolucao'] ?? '') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Status:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e(ucfirst($testdrive->status ?? $testdrive['status'] ?? '')) ?>" readonly>
                </div>
                <div class="text-end">
                    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->stop() ?>
