<!-- Hero Section -->
<div class="p-5 mb-4 bg-dark text-white rounded-4 shadow-sm position-relative overflow-hidden hero-banner">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8">
            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2 rounded-pill mb-3">
                <i class="bi bi-stars me-1"></i> Memórias de Família & Jogos Retrô em Mídia Física
            </span>
            <h1 class="display-5 fw-bold mb-3">Memórias e jogos que você pode segurar nas mãos.</h1>
            <p class="lead text-light-subtle mb-4">
                Guarde suas fotos de família, vídeos de momentos importantes (casamentos, aniversários, viagens), memórias pessoais inesquecíveis ou suas coleções de jogos favoritos em dispositivos físicos exclusivos com acabamento artesanal e conexão USB!
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="#vitrine" class="btn btn-primary btn-lg px-4 rounded-pill fw-semibold">
                    <i class="bi bi-grid-fill me-2"></i> Explorar Dispositivos & Modelos
                </a>
                <a href="<?= View::url('/meus-pedidos') ?>" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                    <i class="bi bi-bag-check me-2"></i> Meus Pedidos
                </a>
            </div>
        </div>
        <div class="col-lg-4 text-center d-none d-lg-block">
            <div class="d-flex justify-content-center gap-3">
                <i class="bi bi-camera-fill text-white-50" style="font-size: 5rem;"></i>
                <i class="bi bi-usb-drive-fill text-white-50" style="font-size: 6rem;"></i>
                <i class="bi bi-controller text-white-50" style="font-size: 5rem;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtros e Busca -->
<div id="vitrine" class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="<?= View::url('/catalogo') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Buscar por modelo, fotos de família, jogos..." value="<?= htmlspecialchars($busca ?? '') ?>">
            </div>
        </div>

        <div class="col-md-4">
            <select name="genero" class="form-select">
                <option value="todos">Todas as Finalidades & Categorias</option>
                <option value="Fotos & Lembranças de Família" <?= ($filtroGenero === 'Fotos & Lembranças de Família') ? 'selected' : '' ?>>📸 Fotos & Lembranças de Família</option>
                <option value="Casamento & Momentos Especiais" <?= ($filtroGenero === 'Casamento & Momentos Especiais') ? 'selected' : '' ?>>💍 Casamento & Momentos Especiais</option>
                <option value="Plataforma" <?= ($filtroGenero === 'Plataforma') ? 'selected' : '' ?>>🎮 Jogos - Plataforma</option>
                <option value="RPG Clássico" <?= ($filtroGenero === 'RPG Clássico') ? 'selected' : '' ?>>🎮 Jogos - RPG Clássico</option>
                <option value="Arcade / Coletânea Multi-Jogos" <?= ($filtroGenero === 'Arcade / Coletânea Multi-Jogos') ? 'selected' : '' ?>>🕹️ Coletâneas de Jogos</option>
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Filtrar</button>
            <?php if (!empty($busca) || !empty($filtroPlataforma) || !empty($filtroGenero) || !empty($filtroFormato)): ?>
                <a href="<?= View::url('/catalogo') ?>" class="btn btn-outline-secondary" title="Limpar Filtros">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Filtro Rápido por Formato Físico -->
    <div class="d-flex flex-wrap gap-2 pt-3 mt-2 border-top">
        <span class="text-muted small align-self-center me-1"><i class="bi bi-hdd-stack me-1"></i> Formato Físico:</span>
        <a href="<?= View::url('/catalogo') ?>" class="btn <?= empty($filtroFormato) ? 'btn-dark' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3">
            Todos os Formatos
        </a>
        <?php foreach ($formatos as $f): ?>
            <a href="<?= View::url('/catalogo?formato=' . $f['id_formato']) ?>" class="btn <?= ($filtroFormato == $f['id_formato']) ? 'btn-dark' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3">
                <i class="bi <?= htmlspecialchars($f['icone']) ?> me-1"></i>
                <?= htmlspecialchars($f['nome']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Grid de Produtos / Dispositivos -->
<?php if (empty($dispositivos)): ?>
    <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white">
        <i class="bi bi-camera-reels text-muted mb-3" style="font-size: 3.5rem;"></i>
        <h4 class="fw-bold">Nenhum produto ou modelo encontrado</h4>
        <p class="text-muted">Tente alterar os termos de busca ou selecionar outro formato de armazenamento.</p>
        <div>
            <a href="<?= View::url('/catalogo') ?>" class="btn btn-outline-primary rounded-pill px-4">Ver Todos os Modelos</a>
        </div>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($dispositivos as $disp): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 device-card transition-all bg-white">
                    <!-- Topo do Card com Formato e Ícone -->
                    <div class="device-card-header p-4 text-center position-relative">
                        <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-3 py-2 position-absolute top-0 end-0 m-3 shadow-xs">
                            <i class="bi <?= htmlspecialchars($disp['formato_icone']) ?> me-1"></i>
                            <?= htmlspecialchars($disp['formato_nome']) ?>
                        </span>
                        
                        <div class="device-icon-container my-3">
                            <i class="bi <?= htmlspecialchars($disp['formato_icone']) ?> device-display-icon"></i>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            <span class="badge bg-primary-subtle text-primary">
                                <?= htmlspecialchars($disp['genero'] ?? 'Memórias & Armazenamento') ?>
                            </span>
                            <?php if (!empty($disp['plataforma'])): ?>
                                <span class="badge bg-light text-dark border">
                                    <?= htmlspecialchars($disp['plataforma']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-2">
                            <?= htmlspecialchars($disp['nome']) ?>
                        </h5>

                        <p class="card-text text-muted small flex-grow-1 line-clamp-3">
                            <?= htmlspecialchars($disp['descricao']) ?>
                        </p>

                        <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">A partir de</small>
                                <span class="fs-4 fw-bold text-primary"><?= View::money($disp['preco']) ?></span>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                <i class="bi bi-check-circle me-1"></i> Disponível
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 pt-0 pb-4 px-4 d-grid gap-2">
                        <a href="<?= View::url('/dispositivo/' . $disp['id_dispositivo']) ?>" class="btn btn-outline-primary rounded-3 fw-semibold">
                            <i class="bi bi-info-circle me-1"></i> Detalhes & Capacidades
                        </a>
                        <a href="<?= View::url('/pedido/configurar/' . $disp['id_dispositivo'] . '?tipo=personalizado') ?>" class="btn btn-primary rounded-3 fw-semibold shadow-xs">
                            <i class="bi bi-magic me-1"></i> Personalizar (Fotos, Vídeos ou Jogos)
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
