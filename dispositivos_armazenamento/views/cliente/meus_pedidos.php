<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Meus Pedidos</h3>
        <p class="text-muted small mb-0">Acompanhe seus pedidos de dispositivos prontos e personalizados (RF14).</p>
    </div>
    <a href="<?= View::url('/catalogo') ?>" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Fazer Novo Pedido
    </a>
</div>

<?php if (empty($pedidos)): ?>
    <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white">
        <i class="bi bi-bag-x text-muted mb-3" style="font-size: 3.5rem;"></i>
        <h4 class="fw-bold">Você ainda não realizou nenhum pedido</h4>
        <p class="text-muted">Explore nossos modelos de dispositivos e escolha o seu com a capacidade ideal!</p>
        <div class="mt-2">
            <a href="<?= View::url('/catalogo') ?>" class="btn btn-primary rounded-pill px-4">Ir para o Catálogo</a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"># Pedido</th>
                        <th>Dispositivo</th>
                        <th>Formato</th>
                        <th>Capacidade</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Status Pedido</th>
                        <th>Personalização</th>
                        <th class="text-end pe-4">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= (int)$p['id_pedido'] ?></td>
                            <td class="fw-semibold text-dark">
                                <?= htmlspecialchars($p['dispositivo_nome']) ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= htmlspecialchars($p['formato_nome']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                    <?= Capacidade::formatLabel($p['capacidade_gb']) ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= View::date($p['data_pedido']) ?></td>
                            <td class="fw-bold text-primary"><?= View::money($p['valor_total']) ?></td>
                            <td><?= View::statusBadge($p['status']) ?></td>
                            <td>
                                <?php if (!empty($p['id_personalizacao'])): ?>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">
                                        <i class="bi bi-magic me-1"></i> <?= htmlspecialchars($p['personalizacao_status']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">Padrão</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?= View::url('/meus-pedidos/' . $p['id_pedido']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
