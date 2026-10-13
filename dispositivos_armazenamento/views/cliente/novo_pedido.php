<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= View::url('/') ?>" class="text-decoration-none">Início</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/catalogo') ?>" class="text-decoration-none">Catálogo</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/dispositivo/' . $dispositivo['id_dispositivo']) ?>" class="text-decoration-none"><?= htmlspecialchars($dispositivo['nome']) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page">Configurar Pedido & Personalização</li>
    </ol>
</nav>

<form action="<?= View::url('/pedido/salvar') ?>" method="POST" id="orderForm">
    <?= View::csrfField() ?>
    <input type="hidden" name="id_dispositivo" value="<?= (int)$dispositivo['id_dispositivo'] ?>">
    <input type="hidden" id="base_price" value="<?= (float)$dispositivo['preco'] ?>">

    <div class="row g-4">
        <!-- Coluna de Configuração Principal -->
        <div class="col-lg-8">
            <!-- 1. Dispositivo / Modelo Selecionado -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-3 fs-3">
                        <i class="bi <?= htmlspecialchars($dispositivo['formato_icone'] ?? 'bi-hdd') ?>"></i>
                    </div>
                    <div>
                        <span class="badge bg-secondary mb-1"><?= htmlspecialchars($dispositivo['formato_nome'] ?? 'Mídia Física') ?></span>
                        <h4 class="fw-bold mb-1"><?= htmlspecialchars($dispositivo['nome']) ?></h4>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($dispositivo['descricao']) ?></p>
                    </div>
                </div>
            </div>

            <!-- 2. Escolha da Capacidade de Armazenamento (RF06, RF07, CA06) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-2">
                    <i class="bi bi-device-ssd me-2 text-primary"></i> 1. Escolha a Capacidade de Armazenamento
                </h5>
                <p class="text-muted small mb-3">Selecione o espaço necessário para guardar suas fotos de família, vídeos de momentos importantes, lembranças ou jogos:</p>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php foreach ($capacidades as $idx => $cap): ?>
                        <div class="col">
                            <label class="card h-100 border p-3 cursor-pointer capacity-option <?= ($idx === 0) ? 'border-primary bg-primary-subtle' : '' ?>" style="cursor: pointer;">
                                <div class="form-check">
                                    <input class="form-check-input capacity-radio" type="radio" name="id_capacidade" 
                                           id="cap_<?= $cap['id_capacidade'] ?>" 
                                           value="<?= $cap['id_capacidade'] ?>" 
                                           data-additional="<?= (float)$cap['preco_adicional'] ?>"
                                           data-label="<?= Capacidade::formatLabel($cap['capacidade_gb']) ?>"
                                           <?= ($idx === 0) ? 'checked' : '' ?> required>
                                    <span class="form-check-label fw-bold d-block text-dark fs-5">
                                        <?= Capacidade::formatLabel($cap['capacidade_gb']) ?>
                                    </span>
                                </div>
                                <div class="mt-2 text-muted small ps-4">
                                    <?php if ($cap['preco_adicional'] > 0): ?>
                                        <span class="text-primary fw-semibold">+ <?= View::money($cap['preco_adicional']) ?></span>
                                    <?php else: ?>
                                        <span class="text-success fw-semibold">Incluso no valor base</span>
                                    <?php endif; ?>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 3. Tipo de Pedido: Modelo Pronto ou Personalizado (RF08, RF09, RF12, CA07) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-2">
                    <i class="bi bi-palette me-2 text-primary"></i> 2. Tipo de Pedido & Personalização
                </h5>
                <p class="text-muted small mb-3">Você pode pedir o dispositivo pronto de fábrica ou solicitar personalização exclusiva para guardar fotos de momentos marcantes, lembranças de família ou jogos:</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="card h-100 border p-3 cursor-pointer order-type-card <?= !$personalizado ? 'border-primary bg-primary-subtle' : '' ?>">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_pedido" id="tipo_pronto" value="pronto" <?= !$personalizado ? 'checked' : '' ?>>
                                <span class="form-check-label fw-bold text-dark">
                                    <i class="bi bi-box-seam me-1"></i> Modelo Padrão (Sem Personalização)
                                </span>
                            </div>
                            <p class="text-muted small mt-2 mb-0 ps-4">
                                Dispositivo montado no modelo original limpo, pronto para você plugar no computador e transferir seus arquivos livremente.
                            </p>
                        </label>
                    </div>

                    <div class="col-md-6">
                        <label class="card h-100 border p-3 cursor-pointer order-type-card <?= $personalizado ? 'border-primary bg-primary-subtle' : '' ?>">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_pedido" id="tipo_personalizado" value="personalizado" <?= $personalizado ? 'checked' : '' ?>>
                                <span class="form-check-label fw-bold text-primary">
                                    <i class="bi bi-magic me-1"></i> Dispositivo Personalizado (Fotos / Lembranças / Jogos)
                                </span>
                            </div>
                            <p class="text-muted small mt-2 mb-0 ps-4">
                                Personalização com gravação a laser na carcaça (nomes, datas), acabamento especial e preparação para suas memórias (+ R$ 25,00).
                            </p>
                        </label>
                    </div>
                </div>

                <!-- Formulário Completo de Personalização de Lembranças e Memórias (RF08, RF09) -->
                <div id="secao_personalizacao" class="<?= $personalizado ? '' : 'd-none' ?> border-top pt-4">
                    <div class="alert alert-info border-0 rounded-3 mb-4 d-flex align-items-center gap-3">
                        <i class="bi bi-camera-fill fs-3 text-primary"></i>
                        <div>
                            <strong>Guarde momentos inesquecíveis em mídia física!</strong><br>
                            <span class="small">Você pode pedir para gravarmos o nome da sua família, homenagens, datas especiais e preparar a mídia para fotos de casamento, viagens, aniversários ou jogos.</span>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="tipo_conteudo" class="form-label fw-semibold">
                                Finalidade Principal das Memórias / Conteúdo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="tipo_conteudo" name="tipo_conteudo">
                                <option value="Fotos de Família & Momentos Especiais" selected>📸 Fotos de Família & Momentos Especiais</option>
                                <option value="Casamento, Bodas & Eventos Marcantes">💍 Casamento, Bodas & Eventos Marcantes</option>
                                <option value="Viagens, Férias & Lembranças Pessoais">✈️ Viagens, Férias & Lembranças Pessoais</option>
                                <option value="Álbum de Infância / Nascimento do Bebê">👶 Álbum de Infância / Nascimento do Bebê</option>
                                <option value="Homenagem Familiar / Recordações Históricas">🕊️ Homenagem Familiar / Recordações Históricas</option>
                                <option value="Jogos Retrô & Emuladores Clássicos">🎮 Jogos Retrô & Emuladores Clássicos</option>
                                <option value="Trilhas Sonoras & Playlists Nostálgicas">🎵 Trilhas Sonoras & Playlists Nostálgicas</option>
                                <option value="Portfólio Profissional de Fotos e Vídeos">💼 Portfólio Profissional de Fotos e Vídeos</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="texto_gravacao" class="form-label fw-semibold">
                                Gravação a Laser na Carcaça / Etiqueta
                            </label>
                            <input type="text" class="form-control" id="texto_gravacao" name="texto_gravacao" 
                                   placeholder="Ex.: Família Silva - 2024 / Nossas Memórias / Casamento Lucas & Mari">
                            <small class="text-muted">Nome, frase ou data que será gravada na carcaça externa.</small>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="cor_acabamento" class="form-label fw-semibold">Cor / Acabamento Desejado</label>
                            <select class="form-select" id="cor_acabamento" name="cor_acabamento">
                                <option value="Padrão Clássico">Padrão Clássico Original</option>
                                <option value="Madeira Nogueira Rústica">Madeira Nogueira Rústica (Ideal para Casamento/Família)</option>
                                <option value="Cristal Translúcido com LED">Cristal Translúcido com LED (Iluminação interna)</option>
                                <option value="Edição Dark / Black Stealth">Edição Dark / Black Stealth</option>
                                <option value="Vintage Retrô Anos 80 e 90">Vintage Retrô Anos 80 e 90</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Organização das Pastas na Mídia</label>
                            <div class="p-2 bg-light rounded-3 small text-muted">
                                <i class="bi bi-folder-check text-success me-1"></i> Criaremos pastas organizadas (ex.: <em>Fotos/</em>, <em>Vídeos/</em>, <em>Momentos/</em> ou <em>Jogos/</em>).
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao_personalizacao" class="form-label fw-semibold">
                            Instruções e Detalhes da Personalização (RF09) <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="descricao_personalizacao" name="descricao_personalizacao" rows="4" 
                                  placeholder="Descreva como deseja sua lembrança. Ex.: Gostaria de organizar as fotos da nossa viagem de família na praia e o vídeo do aniversário de 1 ano do Pedro. Gravar na carcaça 'Família Silva - Nossas Memórias' e deixar espaço livre para colocarmos novos vídeos..."></textarea>
                        <small class="text-muted">Explique quais lembranças, fotos, vídeos ou jogos serão guardados e como prefere a personalização.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna de Resumo Financeiro e Confirmação -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 90px;">
                <h5 class="fw-bold text-dark mb-3">Resumo do Pedido</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Dispositivo Base:</span>
                    <span class="fw-semibold text-dark"><?= View::money($dispositivo['preco']) ?></span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Capacidade (<span id="resumo_cap_label">32 GB</span>):</span>
                    <span class="fw-semibold text-dark" id="resumo_cap_preco">+ R$ 0,00</span>
                </div>

                <div class="d-flex justify-content-between mb-3" id="resumo_pers_linha" style="<?= $personalizado ? '' : 'display: none !important;' ?>">
                    <span class="text-muted">Serviço de Personalização:</span>
                    <span class="fw-semibold text-primary">+ R$ 25,00</span>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fs-5 fw-bold text-dark">Valor Total:</span>
                    <span class="fs-3 fw-bold text-primary" id="resumo_total"><?= View::money($dispositivo['preco'] + ($personalizado ? 25 : 0)) ?></span>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-check-lg me-1"></i> Confirmar Pedido
                </button>

                <div class="text-center mt-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-shield-check text-success me-1"></i> Memórias gravadas com segurança e privacidade (RNF08).
                    </small>
                    <small class="text-muted">Garantia de funcionamento e suporte dedicado.</small>
                </div>
            </div>
        </div>
    </div>
</form>
