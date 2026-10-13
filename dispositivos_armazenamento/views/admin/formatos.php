<div class="row g-4">
    <!-- Formulário de Criação -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold mb-1">Cadastrar Formato</h5>
            <p class="text-muted small mb-4">Adicione novos tipos físicos (ex: Fita Cassete, Cartucho Retrô, Bloco LED).</p>

            <form action="<?= View::url('/admin/formatos/salvar') ?>" method="POST">
                <?= View::csrfField() ?>
                
                <div class="mb-3">
                    <label for="nome" class="form-label fw-semibold">Nome do Formato <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex.: Chaveiro Cápsula de Metal">
                </div>

                <div class="mb-3">
                    <label for="icone" class="form-label fw-semibold">Ícone (Bootstrap Icon)</label>
                    <input type="text" class="form-control" id="icone" name="icone" value="bi-hdd" placeholder="bi-hdd, bi-controller, etc.">
                </div>

                <div class="mb-4">
                    <label for="descricao" class="form-label fw-semibold">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Detalhes dos materiais e acabamento..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Salvar Formato
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Formatos -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0">Formatos Físicos Cadastrados</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Ícone</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th class="text-end pe-4">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($formatos as $f): ?>
                            <tr>
                                <td class="ps-4">
                                    <i class="bi <?= htmlspecialchars($f['icone']) ?> fs-4 text-primary"></i>
                                </td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($f['nome']) ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($f['descricao'] ?? '-') ?></td>
                                <td class="text-end pe-4">
                                    <form action="<?= View::url('/admin/formatos/remover/' . $f['id_formato']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Deseja excluir este formato?');">
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
