<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= View::url('/') ?>" class="text-decoration-none">Início</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/funcionario/pedidos') ?>" class="text-decoration-none">Pedidos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pedido #<?= (int)$pedido['id_pedido'] ?></li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Detalhes do Pedido e Itens -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Pedido #<?= (int)$pedido['id_pedido'] ?></h4>
                    <span class="text-muted small">Registrado no banco de dados em <?= View::date($pedido['data_pedido']) ?></span>
                </div>
                <div>
                    <span class="me-2 small text-muted">Status do Pedido:</span>
                    <?= View::statusBadge($pedido['status']) ?>
                </div>
            </div>

            <div class="row align-items-center mb-4">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <div class="p-3 bg-light rounded-4">
                        <i class="bi bi-usb-symbol display-3 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-9">
                    <span class="badge bg-secondary mb-1"><?= htmlspecialchars($pedido['formato_nome']) ?></span>
                    <h5 class="fw-bold mb-2"><?= htmlspecialchars($pedido['dispositivo_nome']) ?></h5>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                            <i class="bi bi-device-ssd me-1"></i> Capacidade: <?= Capacidade::formatLabel($pedido['capacidade_gb']) ?>
                        </span>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            Base: <?= View::money($pedido['dispositivo_preco']) ?>
                        </span>
                        <?php if ($pedido['capacidade_preco_adicional'] > 0): ?>
                            <span class="badge bg-light text-dark border px-3 py-2">
                                Adicional: +<?= View::money($pedido['capacidade_preco_adicional']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Dados da Personalização (RF10, CA08) -->
            <?php if (!empty($pedido['id_personalizacao'])): ?>
                <div class="border rounded-4 p-4 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-magic me-1 text-primary"></i> Especificações de Personalização
                        </h6>
                        <?= View::statusBadge($pedido['personalizacao_status']) ?>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tipo de Conteúdo Selecionado:</small>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($pedido['personalizacao_tipo_conteudo'] ?? 'Padrão') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Texto para Gravação:</small>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($pedido['personalizacao_texto_gravacao'] ?: 'Nenhum texto gravado') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Acabamento / Cor:</small>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($pedido['personalizacao_cor_acabamento'] ?? 'Padrão') ?></span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Instruções do Cliente:</small>
                            <p class="mb-0 bg-white p-3 rounded-3 border small text-dark">
                                <?= nl2br(htmlspecialchars($pedido['personalizacao_descricao'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-secondary mb-0">
                    <i class="bi bi-box me-1"></i> Pedido de modelo padrão sem personalização adicional.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Coluna de Ações do Funcionário (RF17, CA11) -->
    <div class="col-lg-4">
        <!-- Formulário de Atualização de Status -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold text-dark mb-3">
                <i class="bi bi-arrow-repeat me-1 text-primary"></i> Atualizar Status do Pedido (RF17)
            </h6>

            <form action="<?= View::url('/funcionario/pedidos/atualizar-status') ?>" method="POST">
                <?= View::csrfField() ?>
                <input type="hidden" name="id_pedido" value="<?= (int)$pedido['id_pedido'] ?>">

                <div class="mb-3">
                    <label for="status" class="form-label small fw-semibold">Novo Status</label>
                    <select name="status" id="status" class="form-select">
                        <?php 
                        $opcoes = ['Pendente', 'Em Processamento', 'Em Produção', 'Pronto para Envio', 'Enviado', 'Concluído', 'Cancelado'];
                        foreach ($opcoes as $op):
                        ?>
                            <option value="<?= $op ?>" <?= ($pedido['status'] === $op) ? 'selected' : '' ?>><?= $op ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    Salvar Novo Status
                </button>
            </form>
        </div>

        <!-- Se tiver personalização, formulário de atualização do status dela -->
        <?php if (!empty($pedido['id_personalizacao'])): ?>
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-sliders me-1 text-info"></i> Status da Personalização
                </h6>

                <form action="<?= View::url('/funcionario/personalizacoes/atualizar-status') ?>" method="POST">
                    <?= View::csrfField() ?>
                    <input type="hidden" name="id_personalizacao" value="<?= (int)$pedido['id_personalizacao'] ?>">

                    <div class="mb-3">
                        <label for="status_pers" class="form-label small fw-semibold">Etapa da Personalização</label>
                        <select name="status" id="status_pers" class="form-select">
                            <?php 
                            $statusPersOpcoes = ['Em Análise', 'Aprovada', 'Em Produção', 'Finalizada', 'Rejeitada'];
                            foreach ($statusPersOpcoes as $sp):
                            ?>
                                <option value="<?= $sp ?>" <?= ($pedido['personalizacao_status'] === $sp) ? 'selected' : '' ?>><?= $sp ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-outline-info w-100 fw-semibold">
                        Atualizar Personalização
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Dados do Cliente -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold text-dark mb-3">Dados do Cliente</h6>
            <div class="small">
                <p class="mb-1"><strong>Nome:</strong> <?= htmlspecialchars($pedido['usuario_nome']) ?></p>
                <p class="mb-1"><strong>E-mail:</strong> <?= htmlspecialchars($pedido['usuario_email']) ?></p>
                <p class="mb-0"><strong>ID Usuário:</strong> #<?= (int)$pedido['id_usuario'] ?></p>
            </div>
        </div>

        <!-- Resumo Financeiro -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-2">Valor Total do Pedido</h6>
            <span class="display-6 fw-bold text-primary"><?= View::money($pedido['valor_total']) ?></span>
        </div>
    </div>
</div>
