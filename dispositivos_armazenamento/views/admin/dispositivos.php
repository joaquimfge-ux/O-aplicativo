<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gerenciamento de Produtos (Jogos em Mídia Física)</h3>
        <p class="text-muted small mb-0">Cadastre, edite ou remova os títulos de jogos disponíveis na loja (RF20, RF21, RF22, RF23).</p>
    </div>
    <a href="<?= View::url('/admin/produtos/novo') ?>" class="btn btn-primary rounded-pill px-4 shadow-xs">
        <i class="bi bi-plus-lg me-1"></i> Cadastrar Novo Produto (Jogo)
    </a>
</div>

<!-- Barra de Filtros e Pesquisa de Jogos -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="<?= View::url('/admin/produtos') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Buscar por título, estúdio ou gênero..." value="<?= htmlspecialchars($busca ?? '') ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="plataforma" class="form-select">
                <option value="todos">Todas as Plataformas</option>
                <?php foreach ($plataformas as $p): ?>
                    <option value="<?= htmlspecialchars($p) ?>" <?= ($plataformaSel === $p) ? 'selected' : '' ?>>
                        🎮 <?= htmlspecialchars($p) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="genero" class="form-select">
                <option value="todos">Todos os Gêneros</option>
                <?php foreach ($generos as $g): ?>
                    <option value="<?= htmlspecialchars($g) ?>" <?= ($generoSel === $g) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($g) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Filtrar</button>
            <?php if (!empty($busca) || !empty($plataformaSel) || !empty($generoSel) || !empty($formatoSel)): ?>
                <a href="<?= View::url('/admin/produtos') ?>" class="btn btn-outline-secondary" title="Limpar Filtros">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tabela de Produtos (Jogos) -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Título do Jogo / Produto</th>
                    <th>Plataforma & Gênero</th>
                    <th>Formato Físico</th>
                    <th>Preço Base</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dispositivos)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-controller fs-1 d-block mb-2 text-muted"></i>
                            Nenhum produto (jogo) encontrado para os filtros selecionados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dispositivos as $disp): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= (int)$disp['id_dispositivo'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary-subtle text-primary p-2 rounded-3 fs-4 text-center" style="width: 48px; height: 48px;">
                                        <i class="bi <?= htmlspecialchars($disp['formato_icone']) ?>"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block text-dark fs-6"><?= htmlspecialchars($disp['nome']) ?></strong>
                                        <small class="text-muted">
                                            <?= !empty($disp['desenvolvedora']) ? htmlspecialchars($disp['desenvolvedora']) : 'Estúdio Clássico' ?>
                                            <?= !empty($disp['ano_lancamento']) ? ' &bull; ' . (int)$disp['ano_lancamento'] : '' ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-dark mb-1 d-inline-block">
                                    🎮 <?= htmlspecialchars($disp['plataforma'] ?? 'Console') ?>
                                </span>
                                <br>
                                <span class="badge bg-secondary-subtle text-secondary">
                                    <?= htmlspecialchars($disp['genero'] ?? 'Geral') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi <?= htmlspecialchars($disp['formato_icone']) ?> me-1"></i>
                                    <?= htmlspecialchars($disp['formato_nome']) ?>
                                </span>
                            </td>
                            <td class="fw-bold text-primary fs-6"><?= View::money($disp['preco']) ?></td>
                            <td>
                                <?php if ($disp['ativo']): ?>
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i> Disponível
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="bi bi-pause-circle me-1"></i> Inativo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a href="<?= View::url('/admin/produtos/editar/' . $disp['id_dispositivo']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-pencil me-1"></i> Editar
                                    </a>
                                    
                                    <form action="<?= View::url('/admin/produtos/remover/' . $disp['id_dispositivo']) ?>" method="POST" onsubmit="return confirm('Deseja realmente remover ou inativar o jogo <?= htmlspecialchars(addslashes($disp['nome'])) ?>?');">
                                        <?= View::csrfField() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            <i class="bi bi-trash me-1"></i> Remover
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
