<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= View::url('/') ?>" class="text-decoration-none">Início</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/catalogo') ?>" class="text-decoration-none">Catálogo</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($dispositivo['nome']) ?></li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <!-- Imagem/Ícone do Dispositivo -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white h-100 d-flex flex-column justify-content-center align-items-center">
            <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 rounded-pill mb-4">
                <i class="bi <?= htmlspecialchars($dispositivo['formato_icone'] ?? 'bi-hdd') ?> me-1"></i>
                Formato Físico: <?= htmlspecialchars($dispositivo['formato_nome'] ?? 'Mídia Customizada') ?>
            </span>

            <div class="device-icon-container my-4" style="height: 180px;">
                <i class="bi <?= htmlspecialchars($dispositivo['formato_icone'] ?? 'bi-hdd') ?>" style="font-size: 7rem; color: #4361ee;"></i>
            </div>

            <div class="mt-auto w-100">
                <div class="p-3 bg-light rounded-3 text-start small">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-camera-fill text-primary fs-5"></i>
                        <span>Ideal para fotos de família, vídeos de momentos importantes ou jogos.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-usb-symbol text-success fs-5"></i>
                        <span>Conexão USB 3.0 / 3.2 de alta velocidade Plug & Play.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-palette text-warning fs-5"></i>
                        <span>Disponível como modelo padrão ou com personalização a laser na carcaça.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações e Seleção -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            <div class="d-flex flex-wrap gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 rounded-pill">
                    <?= htmlspecialchars($dispositivo['genero'] ?? 'Memórias & Armazenamento') ?>
                </span>
                <?php if (!empty($dispositivo['plataforma'])): ?>
                    <span class="badge bg-light text-dark border fs-6 px-3 py-2 rounded-pill">
                        <?= htmlspecialchars($dispositivo['plataforma']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <h2 class="fw-bold mb-2 text-dark"><?= htmlspecialchars($dispositivo['nome']) ?></h2>
            
            <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($dispositivo['descricao'])) ?></p>

            <div class="p-3 bg-light rounded-3 mb-4">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-info-circle me-1 text-primary"></i> Sobre o Formato Físico do Armazenamento</h6>
                <p class="text-muted small mb-0"><?= htmlspecialchars($dispositivo['formato_descricao']) ?></p>
            </div>

            <!-- Preço Base -->
            <div class="mb-4">
                <span class="text-muted small d-block">Preço base do dispositivo:</span>
                <span class="display-6 fw-bold text-primary"><?= View::money($dispositivo['preco']) ?></span>
                <span class="text-muted small ms-2">(para a capacidade inicial)</span>
            </div>

            <!-- Consulta de Capacidades Disponíveis (RF06, CA06) -->
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-device-ssd me-1 text-primary"></i> Opções de Memória Disponíveis (RF06):
                </h6>
                <div class="row row-cols-2 row-cols-sm-3 g-2">
                    <?php foreach ($capacidades as $cap): ?>
                        <div class="col">
                            <div class="border rounded-3 p-3 text-center bg-light">
                                <span class="d-block fw-bold fs-5 text-dark"><?= Capacidade::formatLabel($cap['capacidade_gb']) ?></span>
                                <small class="text-muted">
                                    <?= ($cap['preco_adicional'] > 0) ? '+' . View::money($cap['preco_adicional']) : 'Sem adicional' ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Botões de Ação para Compra (RF05, RF07, RF08) -->
            <div class="pt-3 border-top">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <a href="<?= View::url('/pedido/configurar/' . $dispositivo['id_dispositivo'] . '?tipo=pronto') ?>" class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold">
                            <i class="bi bi-cart-check me-1"></i> Pedir Modelo Pronto (Padrão)
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="<?= View::url('/pedido/configurar/' . $dispositivo['id_dispositivo'] . '?tipo=personalizado') ?>" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">
                            <i class="bi bi-magic me-1"></i> Personalizar (Fotos, Vídeos ou Jogos)
                        </a>
                    </div>
                </div>
                <small class="text-muted d-block text-center mt-2">
                    A personalização permite gravar nomes de família, datas de casamento/aniversário, escolher acabamento da carcaça e organizar suas fotos, vídeos ou jogos.
                </small>
            </div>
        </div>
    </div>
</div>
