<div class="row justify-content-center my-5">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="feature-icon-circle mx-auto bg-primary-subtle text-primary mb-3">
                        <i class="bi bi-shield-lock-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Acesse sua Conta</h3>
                    <p class="text-muted small">Entre com seu e-mail e senha para gerenciar seus dispositivos e pedidos.</p>
                </div>

                <form action="<?= View::url('/login') ?>" method="POST" novalidate>
                    <?= View::csrfField() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">E-mail</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" class="form-control border-start-0" id="email" name="email" required placeholder="seu@email.com" autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="senha" class="form-label fw-semibold mb-0">Senha</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" class="form-control border-start-0" id="senha" name="senha" required placeholder="Sua senha secreta">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted small mb-0">
                        Ainda não tem conta? <a href="<?= View::url('/cadastro') ?>" class="text-primary fw-semibold text-decoration-none">Cadastre-se gratuitamente</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Card de Demonstração Rápida com Contas de Teste -->
        <div class="card shadow-sm border-0 rounded-4 mt-3 bg-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2 text-muted small fw-semibold">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    <span>Acesso Rápido de Teste (Clique para preencher):</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm quick-fill" data-email="admin@sistema.com" data-senha="admin123">
                        <i class="bi bi-person-gear me-1"></i> Admin
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm quick-fill" data-email="funcionario@sistema.com" data-senha="func123">
                        <i class="bi bi-briefcase me-1"></i> Funcionário
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm quick-fill" data-email="cliente@sistema.com" data-senha="cliente123">
                        <i class="bi bi-person me-1"></i> Cliente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
