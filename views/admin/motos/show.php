<?php $this->layout('layouts/admin', ['title' => 'Detalhe do Autor']) ?>

<?php $this->start('body') ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detalhes do Autor</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label class="form-label"><strong>ID:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($autor['id']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Nome:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($autor['nome_autor']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Data de Nascimento:</strong></label>
                    <input type="date" class="form-control"
                        value="<?= $this->e($autor['data_nascimento']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Nacionalidade:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($autor['nacionalidade']) ?>" readonly>
                </div>
                <div class="text-end">
                    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->stop() ?>