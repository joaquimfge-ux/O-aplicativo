<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= View::url('/') ?>" class="text-decoration-none">Início</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/meus-pedidos') ?>" class="text-decoration-none">Meus Pedidos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pedido #<?= (int)$pedido['id_pedido'] ?></li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Detalhes do Dispositivo e Pedido -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Pedido #<?= (int)$pedido['id_pedido'] ?></h4>
                    <span class="text-muted small">Realizado em <?= View::date($pedido['data_pedido']) ?></span>
                </div>
                <div>
                    <span class="fs-6 me-1">Status:</span>
                    <?= View::statusBadge($pedido['status']) ?>
                </div>
            </div>

            <div class="row align-items-center mb-4">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <div class="p-3 bg-light rounded-4">
                        <i class="bi bi-usb-drive-fill display-3 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-9">
                    <span class="badge bg-secondary mb-1"><?= htmlspecialchars($pedido['formato_nome']) ?></span>
                    <h5 class="fw-bold mb-2"><?= htmlspecialchars($pedido['dispositivo_nome']) ?></h5>
                    <p class="text-muted small mb-2"><?= htmlspecialchars($pedido['descricao'] ?? '') ?></p>
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

            <!-- Dados da Personalização (se houver) -->
            <?php if (!empty($pedido['id_personalizacao'])): ?>
                <div class="border rounded-4 p-4 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-magic me-1 text-primary"></i> Solicitação de Personalização Exclusiva
                        </h6>
                        <?= View::statusBadge($pedido['personalizacao_status']) ?>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tipo de Conteúdo / Memória:</small>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($pedido['personalizacao_tipo_conteudo'] ?? 'Padrão') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Gravação na Carcaça:</small>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($pedido['personalizacao_texto_gravacao'] ?: 'Nenhum texto gravado') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Acabamento / Cor:</small>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($pedido['personalizacao_cor_acabamento'] ?? 'Padrão') ?></span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Descrição / Instruções:</small>
                            <p class="mb-0 bg-white p-3 rounded-3 border small text-dark">
                                <?= nl2br(htmlspecialchars($pedido['personalizacao_descricao'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-secondary mb-0">
                    <i class="bi bi-box me-1"></i> Este pedido é de um <strong>modelo pronto</strong>, sem solicitações de personalização.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Coluna Lateral de Resumo Financeiro e Cliente -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold text-dark mb-3">Resumo Financeiro</h6>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Dispositivo:</span>
                <span><?= View::money($pedido['dispositivo_preco']) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Capacidade (<?= Capacidade::formatLabel($pedido['capacidade_gb']) ?>):</span>
                <span>+<?= View::money($pedido['capacidade_preco_adicional']) ?></span>
            </div>
            <?php if (!empty($pedido['id_personalizacao'])): ?>
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Serviço Personalização:</span>
                    <span>+<?= View::money(25.00) ?></span>
                </div>
            <?php endif; ?>
            <hr class="my-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark">Total Pago:</span>
                <span class="fs-4 fw-bold text-primary"><?= View::money($pedido['valor_total']) ?></span>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3">Dados do Cliente</h6>
            <div class="small">
                <p class="mb-1"><strong>Nome:</strong> <?= htmlspecialchars($pedido['usuario_nome']) ?></p>
                <p class="mb-0"><strong>E-mail:</strong> <?= htmlspecialchars($pedido['usuario_email']) ?></p>
            </div>
        </div>
    </div>
</div>
