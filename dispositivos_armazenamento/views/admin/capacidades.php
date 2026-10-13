<div class="row g-4">
    <!-- Formulário de Criação -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold mb-1">Cadastrar Capacidade</h5>
            <p class="text-muted small mb-4">Adicione opções de armazenamento em Gigabytes e o acréscimo de preço (RF06, RF07).</p>

            <form action="<?= View::url('/admin/capacidades/salvar') ?>" method="POST">
                <?= View::csrfField() ?>
                
                <div class="mb-3">
                    <label for="capacidade_gb" class="form-label fw-semibold">Capacidade em GB <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="capacidade_gb" name="capacidade_gb" min="1" required placeholder="Ex.: 2048 (para 2 TB)">
                    <small class="text-muted">Ex: 32, 64, 128, 256, 512, 1024.</small>
                </div>

                <div class="mb-4">
                    <label for="preco_adicional" class="form-label fw-semibold">Preço Adicional (R$) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" step="0.01" min="0" class="form-control" id="preco_adicional" name="preco_adicional" required placeholder="0.00">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Salvar Capacidade
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Capacidades -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0">Capacidades de Memória Disponíveis</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Capacidade</th>
                            <th>Valor em Gigabytes</th>
                            <th>Preço Adicional</th>
                            <th class="text-end pe-4">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($capacidades as $cap): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?= (int)$cap['id_capacidade'] ?></td>
                                <td>
                                    <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
                                        <?= Capacidade::formatLabel($cap['capacidade_gb']) ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?= (int)$cap['capacidade_gb'] ?> GB</td>
                                <td class="fw-bold text-dark">
                                    <?= ($cap['preco_adicional'] > 0) ? '+ ' . View::money($cap['preco_adicional']) : '<span class="text-success">Sem custo adicional</span>' ?>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="<?= View::url('/admin/capacidades/remover/' . $cap['id_capacidade']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Deseja excluir esta opção de capacidade?');">
                                        <?= View::csrfField() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
