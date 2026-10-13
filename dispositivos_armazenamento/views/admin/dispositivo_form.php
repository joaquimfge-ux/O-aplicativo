<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= View::url('/') ?>" class="text-decoration-none">Início</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/admin/dashboard') ?>" class="text-decoration-none">Administração</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/admin/produtos') ?>" class="text-decoration-none">Produtos & Modelos</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $dispositivo ? 'Editar Produto' : 'Novo Produto' ?></li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
            <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                <div class="feature-icon-circle bg-primary-subtle text-primary">
                    <i class="bi bi-box-seam-fill fs-2"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1"><?= $dispositivo ? 'Editar Produto: ' . htmlspecialchars($dispositivo['nome']) : 'Cadastro de Produto (Mídia Física & Armazenamento)' ?></h3>
                    <p class="text-muted small mb-0">Cadastre dispositivos e produtos físicos para guardar fotos de família, memórias de momentos importantes ou jogos clássicos (RF20, RF21).</p>
                </div>
            </div>

            <form action="<?= View::url('/admin/produtos/salvar') ?>" method="POST" novalidate>
                <?= View::csrfField() ?>
                <?php if ($dispositivo): ?>
                    <input type="hidden" name="id_dispositivo" value="<?= (int)$dispositivo['id_dispositivo'] ?>">
                <?php endif; ?>

                <!-- 1. Identificação do Produto e Formato -->
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-tag text-primary me-2"></i> 1. Identificação do Produto & Formato Físico</h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label for="nome" class="form-label fw-semibold">Nome do Produto / Dispositivo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-hdd-fill text-muted"></i></span>
                            <input type="text" class="form-control" id="nome" name="nome" required 
                                   placeholder="Ex.: Pendrive Madeira Nobre - Fotos de Família, Cartucho Retrô SNES..." 
                                   value="<?= htmlspecialchars($dispositivo['nome'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label for="id_formato" class="form-label fw-semibold">Formato Físico de Armazenamento <span class="text-danger">*</span></label>
                        <select name="id_formato" id="id_formato" class="form-select" required>
                            <option value="">Selecione o formato...</option>
                            <?php foreach ($formatos as $f): ?>
                                <option value="<?= $f['id_formato'] ?>" <?= (isset($dispositivo['id_formato']) && $dispositivo['id_formato'] == $f['id_formato']) ? 'selected' : '' ?>>
                                    💾 <?= htmlspecialchars($f['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Chassi físico (Cartucho Retrô, Madeira Nobre, Cristal LED, etc.).</small>
                    </div>
                </div>

                <!-- 2. Finalidade e Compatibilidade -->
                <h5 class="fw-bold text-dark mb-3 pt-3 border-top"><i class="bi bi-stars text-primary me-2"></i> 2. Finalidade das Memórias & Compatibilidade</h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="genero" class="form-label fw-semibold">Finalidade Principal / Categoria <span class="text-danger">*</span></label>
                        <select name="genero" id="genero" class="form-select" required>
                            <option value="Fotos & Lembranças de Família" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'Fotos & Lembranças de Família') ? 'selected' : '' ?>>
                                📸 Fotos & Lembranças de Família
                            </option>
                            <option value="Casamento & Momentos Especiais" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'Casamento & Momentos Especiais') ? 'selected' : '' ?>>
                                💍 Casamento & Momentos Especiais
                            </option>
                            <option value="Viagens & Recordações Pessoais" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'Viagens & Recordações Pessoais') ? 'selected' : '' ?>>
                                ✈️ Viagens & Recordações Pessoais
                            </option>
                            <option value="Homenagens & Recordações Históricas" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'Homenagens & Recordações Históricas') ? 'selected' : '' ?>>
                                🕊️ Homenagens & Recordações Históricas
                            </option>
                            <option value="Plataforma" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'Plataforma') ? 'selected' : '' ?>>
                                🎮 Jogos - Plataforma
                            </option>
                            <option value="RPG Clássico" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'RPG Clássico') ? 'selected' : '' ?>>
                                🎮 Jogos - RPG Clássico
                            </option>
                            <option value="Ação / Aventura" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'Ação / Aventura') ? 'selected' : '' ?>>
                                🎮 Jogos - Ação / Aventura
                            </option>
                            <option value="Arcade / Coletânea Multi-Jogos" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'Arcade / Coletânea Multi-Jogos') ? 'selected' : '' ?>>
                                🕹️ Coletâneas de Jogos Retrô & Arcade
                            </option>
                            <option value="Memórias & Armazenamento Geral" <?= (isset($dispositivo['genero']) && $dispositivo['genero'] === 'Memórias & Armazenamento Geral') ? 'selected' : '' ?>>
                                📁 Armazenamento Geral & Backup Pessoal
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="plataforma" class="form-label fw-semibold">Compatibilidade / Plataforma</label>
                        <select name="plataforma" id="plataforma" class="form-select">
                            <option value="USB Universal (PC / Notebook / TV)" <?= (isset($dispositivo['plataforma']) && $dispositivo['plataforma'] === 'USB Universal (PC / Notebook / TV)') ? 'selected' : '' ?>>
                                💻 USB Universal (PC / Notebook / Smart TV / Mac)
                            </option>
                            <option value="Super Nintendo (SNES)" <?= (isset($dispositivo['plataforma']) && $dispositivo['plataforma'] === 'Super Nintendo (SNES)') ? 'selected' : '' ?>>
                                🎮 Super Nintendo (SNES)
                            </option>
                            <option value="Sega Mega Drive" <?= (isset($dispositivo['plataforma']) && $dispositivo['plataforma'] === 'Sega Mega Drive') ? 'selected' : '' ?>>
                                🎮 Sega Mega Drive
                            </option>
                            <option value="Nintendo 64 (N64)" <?= (isset($dispositivo['plataforma']) && $dispositivo['plataforma'] === 'Nintendo 64 (N64)') ? 'selected' : '' ?>>
                                🎮 Nintendo 64 (N64)
                            </option>
                            <option value="Game Boy / GBA" <?= (isset($dispositivo['plataforma']) && $dispositivo['plataforma'] === 'Game Boy / GBA') ? 'selected' : '' ?>>
                                🎮 Game Boy / GBA
                            </option>
                            <option value="Arcade / MAME" <?= (isset($dispositivo['plataforma']) && $dispositivo['plataforma'] === 'Arcade / MAME') ? 'selected' : '' ?>>
                                🕹️ Arcade / MAME
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="desenvolvedora" class="form-label fw-semibold">Fabricante / Estúdio / Desenvolvedora</label>
                        <input type="text" class="form-control" id="desenvolvedora" name="desenvolvedora" 
                               placeholder="Ex.: CustomStorage Handmade, Nintendo, Studio Fotográfico..." 
                               value="<?= htmlspecialchars($dispositivo['desenvolvedora'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="ano_lancamento" class="form-label fw-semibold">Ano de Referência / Lançamento</label>
                        <input type="number" min="1970" max="2035" class="form-control" id="ano_lancamento" name="ano_lancamento" 
                               placeholder="Ex.: 2024 ou ano do clássico" 
                               value="<?= htmlspecialchars($dispositivo['ano_lancamento'] ?? '') ?>">
                    </div>
                </div>

                <!-- 3. Preço, Imagem e Descrição -->
                <h5 class="fw-bold text-dark mb-3 pt-3 border-top"><i class="bi bi-cash-stack text-success me-2"></i> 3. Preço Base, Imagem e Detalhes da Mídia</h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label for="preco" class="form-label fw-semibold">Preço Base do Produto (R$) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">R$</span>
                            <input type="number" step="0.01" min="1" class="form-control" id="preco" name="preco" required 
                                   placeholder="129.90" 
                                   value="<?= htmlspecialchars($dispositivo['preco'] ?? '') ?>">
                        </div>
                        <small class="text-muted">Valor para a capacidade inicial inclusa.</small>
                    </div>

                    <div class="col-md-7">
                        <label for="imagem" class="form-label fw-semibold">Capa / Imagem da Mídia</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-image text-muted"></i></span>
                            <input type="text" class="form-control" id="imagem" name="imagem" 
                                   placeholder="assets/images/dispositivo_cartucho.svg ou URL da foto" 
                                   value="<?= htmlspecialchars($dispositivo['imagem'] ?? 'assets/images/dispositivo_cartucho.svg') ?>">
                        </div>
                        <small class="text-muted">Caminho da imagem no sistema ou link na web.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label fw-semibold">Descrição / Detalhes da Memória & Mídia</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4" 
                              placeholder="Descreva as características da mídia (ex.: estojo em madeira nobre perfeito para fotos de casamento, cartucho retrô USB para jogos clássicos, velocidade de transferência USB 3.0, gravação a laser disponível)..."><?= htmlspecialchars($dispositivo['descricao'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <div class="card p-3 bg-light border-0 rounded-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="ativo" name="ativo" value="1"
                                   <?= (!isset($dispositivo) || !empty($dispositivo['ativo'])) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="ativo">
                                Produto Ativo e Disponível para Venda no Catálogo
                            </label>
                        </div>
                        <small class="text-muted">Quando desativado, o produto é ocultado da vitrine dos clientes.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="<?= View::url('/admin/produtos') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold shadow-xs">
                        <i class="bi bi-check2-circle me-1"></i> <?= $dispositivo ? 'Salvar Alterações do Produto' : 'Cadastrar Produto' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
