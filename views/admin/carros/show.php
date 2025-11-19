<?php $this->layout('layouts/admin', ['title' => 'Detalhes do carro']) ?>

<?php $this->start('body') ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detalhes do carro</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label class="form-label"><strong>ID:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($carro['id']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Título:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($carro['titulo']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Ano de publicação:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($carro['ano_publicacao']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Gênero:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($carro['genero']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Disponível:</strong></label>
                    <input type="text" class="form-control"
                        value="<?= $carro['disponivel'] ? 'Sim' : 'Não' ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Editora:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($carro['editora_nome']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Autor:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($carro['autor_nome']) ?>" readonly>
                </div>
                <div class="text-end">
                    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->stop() ?>