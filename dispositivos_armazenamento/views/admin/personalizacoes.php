<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gerenciamento de Personalizações - Admin</h3>
        <p class="text-muted small mb-0">Controle das solicitações de personalização de carcaças, gravações e mídias (RF11, CA15).</p>
    </div>
</div>

<!-- Filtros de Status -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="<?= View::url('/admin/personalizacoes') ?>" class="btn <?= empty($filtroStatus) ? 'btn-primary' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3">
        Todas as Solicitações
    </a>
    <?php 
    $statusLista = ['Em Análise', 'Aprovada', 'Em Produção', 'Finalizada', 'Rejeitada'];
    foreach ($statusLista as $st):
    ?>
        <a href="<?= View::url('/admin/personalizacoes?status=' . urlencode($st)) ?>" class="btn <?= ($filtroStatus === $st) ? 'btn-primary' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3">
            <?= $st ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4"># Pedido</th>
                    <th>Cliente</th>
                    <th>Dispositivo</th>
                    <th>Conteúdo</th>
                    <th>Gravação</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($personalizacoes)): ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">Nenhuma solicitação encontrada.</td></tr>
                <?php else: ?>
                    <?php foreach ($personalizacoes as $pers): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= (int)$pers['id_pedido'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($pers['usuario_nome']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($pers['usuario_email']) ?></small>
                            </td>
                            <td>
                                <?= htmlspecialchars($pers['dispositivo_nome']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($pers['formato_nome']) ?> &bull; <?= Capacidade::formatLabel($pers['capacidade_gb']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($pers['tipo_conteudo'] ?? 'Geral') ?></span>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark small">"<?= htmlspecialchars($pers['texto_gravacao'] ?: 'N/A') ?>"</span>
                            </td>
                            <td><?= View::statusBadge($pers['status']) ?></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#adminModalPers_<?= $pers['id_personalizacao'] ?>">
                                    Gerenciar
                                </button>

                                <!-- Modal Admin de Alteração -->
                                <div class="modal fade text-start" id="adminModalPers_<?= $pers['id_personalizacao'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Personalização do Pedido #<?= (int)$pers['id_pedido'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <form action="<?= View::url('/funcionario/personalizacoes/atualizar-status') ?>" method="POST">
                                                <?= View::csrfField() ?>
                                                <input type="hidden" name="id_personalizacao" value="<?= (int)$pers['id_personalizacao'] ?>">
                                                
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted fw-bold">Especificação do Cliente:</label>
                                                        <div class="p-3 bg-light rounded-3 text-dark small">
                                                            <?= nl2br(htmlspecialchars($pers['descricao'])) ?>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Alterar Status da Personalização:</label>
                                                        <select name="status" class="form-select">
                                                            <?php foreach ($statusLista as $st): ?>
                                                                <option value="<?= $st ?>" <?= ($pers['status'] === $st) ? 'selected' : '' ?>><?= $st ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4">Salvar Alteração</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
