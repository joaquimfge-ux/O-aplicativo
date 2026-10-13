<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Painel Geral da Administração</h3>
        <p class="text-muted small mb-0">Visão global dos dispositivos, pedidos, receitas e controle de equipe.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= View::url('/admin/produtos/novo') ?>" class="btn btn-primary rounded-pill px-3 shadow-xs">
            <i class="bi bi-plus-lg me-1"></i> Novo Produto (Jogo)
        </a>
        <a href="<?= View::url('/admin/usuarios/novo') ?>" class="btn btn-outline-primary rounded-pill px-3 shadow-xs">
            <i class="bi bi-person-plus me-1"></i> Novo Usuário
        </a>
    </div>
</div>

<!-- Cards de KPIs Administrativos -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small d-block">Receita Total</span>
                    <h4 class="fw-bold mb-0 text-success"><?= View::money($stats['receita']) ?></h4>
                </div>
                <div class="p-3 bg-success-subtle text-success rounded-3 fs-4">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small d-block">Total de Pedidos</span>
                    <h4 class="fw-bold mb-0 text-primary"><?= $stats['total'] ?></h4>
                </div>
                <div class="p-3 bg-primary-subtle text-primary rounded-3 fs-4">
                    <i class="bi bi-cart3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small d-block">Jogos Cadastrados</span>
                    <h4 class="fw-bold mb-0 text-dark"><?= $totalDispositivos ?></h4>
                </div>
                <div class="p-3 bg-light text-dark rounded-3 fs-4 border">
                    <i class="bi bi-controller"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small d-block">Personalizações Pendentes</span>
                    <h4 class="fw-bold mb-0 text-warning-emphasis"><?= $persPendentes ?></h4>
                </div>
                <div class="p-3 bg-warning-subtle text-warning-emphasis rounded-3 fs-4">
                    <i class="bi bi-palette"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Pedidos Recentes -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Pedidos Recentes</h5>
                <a href="<?= View::url('/admin/pedidos') ?>" class="text-primary small text-decoration-none fw-semibold">Ver Todos</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th># ID</th>
                            <th>Cliente</th>
                            <th>Jogo / Mídia</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidosRecentes)): ?>
                            <tr><td colspan="5" class="text-muted text-center py-4">Nenhum pedido recente.</td></tr>
                        <?php else: ?>
                            <?php foreach ($pedidosRecentes as $pr): ?>
                                <tr>
                                    <td class="fw-bold">#<?= (int)$pr['id_pedido'] ?></td>
                                    <td><?= htmlspecialchars($pr['usuario_nome']) ?></td>
                                    <td><?= htmlspecialchars($pr['dispositivo_nome']) ?></td>
                                    <td class="fw-bold"><?= View::money($pr['valor_total']) ?></td>
                                    <td><?= View::statusBadge($pr['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Estatísticas de Usuários (RF24) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Comunidade de Usuários</h5>
                <a href="<?= View::url('/admin/usuarios') ?>" class="text-primary small text-decoration-none fw-semibold">Gerenciar</a>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-people text-primary fs-5"></i>
                        <span>Clientes Cadastrados</span>
                    </div>
                    <span class="badge bg-primary rounded-pill"><?= $usuarios['cliente'] ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge text-info fs-5"></i>
                        <span>Funcionários da Produção</span>
                    </div>
                    <span class="badge bg-info text-dark rounded-pill"><?= $usuarios['funcionario'] ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock text-dark fs-5"></i>
                        <span>Administradores</span>
                    </div>
                    <span class="badge bg-dark rounded-pill"><?= $usuarios['administrador'] ?></span>
                </li>
            </ul>

            <div class="mt-auto pt-3 border-top">
                <a href="<?= View::url('/admin/usuarios/novo') ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                    <i class="bi bi-person-plus me-1"></i> Cadastrar Novo Usuário
                </a>
            </div>
        </div>
    </div>
</div>
