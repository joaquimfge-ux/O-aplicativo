<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gerenciamento de Usuários e Acessos</h3>
        <p class="text-muted small mb-0">Consulte, cadastre e edite contas de clientes, funcionários e administradores (RF24, RF25, RF26, RF27).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= View::url('/admin/usuarios/novo') ?>" class="btn btn-primary rounded-pill px-4 shadow-xs">
            <i class="bi bi-person-plus-fill me-1"></i> Cadastrar Novo Usuário
        </a>
    </div>
</div>

<!-- Cards de Resumo por Perfil -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <a href="<?= View::url('/admin/usuarios') ?>" class="card border-0 shadow-sm rounded-4 p-3 bg-white text-decoration-none transition-all <?= empty($filtroTipo) ? 'border border-primary' : '' ?>">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-light text-dark rounded-3 fs-4">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Todos os Usuários</span>
                    <h4 class="fw-bold mb-0 text-dark"><?= array_sum($contagem) ?></h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= View::url('/admin/usuarios?tipo=cliente') ?>" class="card border-0 shadow-sm rounded-4 p-3 bg-white text-decoration-none transition-all <?= ($filtroTipo === 'cliente') ? 'border border-primary' : '' ?>">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-primary-subtle text-primary rounded-3 fs-4">
                    <i class="bi bi-bag-heart-fill"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Clientes</span>
                    <h4 class="fw-bold mb-0 text-primary"><?= $contagem['cliente'] ?? 0 ?></h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= View::url('/admin/usuarios?tipo=funcionario') ?>" class="card border-0 shadow-sm rounded-4 p-3 bg-white text-decoration-none transition-all <?= ($filtroTipo === 'funcionario') ? 'border border-primary' : '' ?>">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-info-subtle text-info rounded-3 fs-4">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Funcionários</span>
                    <h4 class="fw-bold mb-0 text-info"><?= $contagem['funcionario'] ?? 0 ?></h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= View::url('/admin/usuarios?tipo=administrador') ?>" class="card border-0 shadow-sm rounded-4 p-3 bg-white text-decoration-none transition-all <?= ($filtroTipo === 'administrador') ? 'border border-primary' : '' ?>">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-dark-subtle text-dark rounded-3 fs-4">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Administradores</span>
                    <h4 class="fw-bold mb-0 text-dark"><?= $contagem['administrador'] ?? 0 ?></h4>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Filtros e Busca -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="<?= View::url('/admin/usuarios') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Buscar por nome, e-mail ou telefone..." value="<?= htmlspecialchars($busca ?? '') ?>">
            </div>
        </div>
        <div class="col-md-4">
            <select name="tipo" class="form-select">
                <option value="todos">Todos os Perfis de Acesso</option>
                <option value="cliente" <?= ($filtroTipo === 'cliente') ? 'selected' : '' ?>>Apenas Clientes</option>
                <option value="funcionario" <?= ($filtroTipo === 'funcionario') ? 'selected' : '' ?>>Apenas Funcionários</option>
                <option value="administrador" <?= ($filtroTipo === 'administrador') ? 'selected' : '' ?>>Apenas Administradores</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Filtrar</button>
            <?php if (!empty($busca) || !empty($filtroTipo)): ?>
                <a href="<?= View::url('/admin/usuarios') ?>" class="btn btn-outline-secondary" title="Limpar">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tabela de Usuários (RF24) -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Nome do Usuário</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Perfil de Acesso</th>
                    <th>Data Cadastro</th>
                    <th>Status da Conta</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            Nenhum usuário encontrado para os filtros selecionados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= (int)$u['id_usuario'] ?></td>
                            <td>
                                <strong class="d-block text-dark"><?= htmlspecialchars($u['nome']) ?></strong>
                            </td>
                            <td>
                                <span class="text-muted"><?= htmlspecialchars($u['email']) ?></span>
                            </td>
                            <td>
                                <?= !empty($u['telefone']) ? htmlspecialchars($u['telefone']) : '<span class="text-muted small">Não informado</span>' ?>
                            </td>
                            <td>
                                <?php if ($u['tipo_usuario'] === 'administrador'): ?>
                                    <span class="badge bg-dark px-2 py-1">🛡️ Administrador</span>
                                <?php elseif ($u['tipo_usuario'] === 'funcionario'): ?>
                                    <span class="badge bg-info text-dark px-2 py-1">🛠️ Funcionário</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-secondary border px-2 py-1">🛍️ Cliente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?= View::date($u['criado_em']) ?></td>
                            <td>
                                <?php if (!empty($u['ativo'])): ?>
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i> Ativa
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-x-circle me-1"></i> Desativada (RF27)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <!-- Botão de Edição (RF26) com redirecionamento para tela dedicada -->
                                    <a href="<?= View::url('/admin/usuarios/editar/' . $u['id_usuario']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-pencil me-1"></i> Editar
                                    </a>

                                    <!-- Botão Desativar/Reativar (RF27) -->
                                    <?php if ($u['id_usuario'] !== Auth::id()): ?>
                                        <form action="<?= View::url('/admin/usuarios/toggle/' . $u['id_usuario']) ?>" method="POST" onsubmit="return confirm('Deseja realmente alterar o status de ativação da conta de <?= htmlspecialchars(addslashes($u['nome'])) ?>?');">
                                            <?= View::csrfField() ?>
                                            <?php if (!empty($u['ativo'])): ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" title="Desativar conta">
                                                    <i class="bi bi-person-x me-1"></i> Desativar
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3" title="Reativar conta">
                                                    <i class="bi bi-person-check me-1"></i> Reativar
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
