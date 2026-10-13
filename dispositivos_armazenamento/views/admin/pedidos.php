<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gerenciamento Geral de Pedidos</h3>
        <p class="text-muted small mb-0">Controle completo de todos os pedidos cadastrados na loja (RF18, RF19).</p>
    </div>
</div>

<!-- Filtros e Busca -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="<?= View::url('/admin/pedidos') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Buscar por cliente, e-mail ou modelo..." value="<?= htmlspecialchars($busca ?? '') ?>">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="todos">Todos os Status</option>
                <?php 
                $opcoes = ['Pendente', 'Em Processamento', 'Em Produção', 'Pronto para Envio', 'Enviado', 'Concluído', 'Cancelado'];
                foreach ($opcoes as $op):
                ?>
                    <option value="<?= $op ?>" <?= ($filtroStatus === $op) ? 'selected' : '' ?>><?= $op ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Filtrar</button>
            <?php if (!empty($busca) || !empty($filtroStatus)): ?>
                <a href="<?= View::url('/admin/pedidos') ?>" class="btn btn-outline-secondary" title="Limpar">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tabela de Pedidos -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Cliente</th>
                    <th>Dispositivo</th>
                    <th>Capacidade</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Personalização</th>
                    <th class="text-end pe-4">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr><td colspan="9" class="text-center py-5 text-muted">Nenhum pedido encontrado.</td></tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= (int)$p['id_pedido'] ?></td>
                            <td>
                                <strong class="d-block text-dark"><?= htmlspecialchars($p['usuario_nome']) ?></strong>
                                <small class="text-muted"><?= htmlspecialchars($p['usuario_email']) ?></small>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($p['dispositivo_nome']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($p['formato_nome']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary">
                                    <?= Capacidade::formatLabel($p['capacidade_gb']) ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= View::date($p['data_pedido']) ?></td>
                            <td class="fw-bold text-dark"><?= View::money($p['valor_total']) ?></td>
                            <td><?= View::statusBadge($p['status']) ?></td>
                            <td>
                                <?php if (!empty($p['id_personalizacao'])): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis">
                                        <i class="bi bi-magic"></i> <?= htmlspecialchars($p['personalizacao_status']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">Padrão</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?= View::url('/funcionario/pedidos/' . $p['id_pedido']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-sliders me-1"></i> Gerenciar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
