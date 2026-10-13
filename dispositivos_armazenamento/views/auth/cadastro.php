<div class="row justify-content-center my-4">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 rounded-4 bg-white">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="feature-icon-circle mx-auto bg-primary-subtle text-primary mb-3">
                        <i class="bi bi-person-plus-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Criar Conta de Usuário</h3>
                    <p class="text-muted small">Cadastre-se para escolher dispositivos físicos para guardar suas fotos de família, memórias marcantes e jogos favoritos.</p>
                </div>

                <form action="<?= View::url('/cadastro') ?>" method="POST" novalidate>
                    <?= View::csrfField() ?>

                    <div class="mb-3">
                        <label for="nome" class="form-label fw-semibold">Nome Completo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" id="nome" name="nome" required placeholder="Ex.: Lucas Mendes" autofocus>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label for="email" class="form-label fw-semibold">E-mail <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" required placeholder="seu@email.com">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="telefone" class="form-label fw-semibold">Telefone / WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-success"></i></span>
                                <input type="text" class="form-control border-start-0" id="telefone" name="telefone" placeholder="(11) 98765-4321">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="senha" class="form-label fw-semibold">Senha <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" class="form-control border-start-0" id="senha" name="senha" minlength="6" required placeholder="Mínimo 6 caracteres">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="confirmar_senha" class="form-label fw-semibold">Confirmar Senha <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                                <input type="password" class="form-control border-start-0" id="confirmar_senha" name="confirmar_senha" minlength="6" required placeholder="Repita a senha">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold rounded-pill shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Concluir Meu Cadastro
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted small mb-0">
                        Já possui uma conta cadastrada? <a href="<?= View::url('/login') ?>" class="text-primary fw-semibold text-decoration-none">Faça login aqui</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
