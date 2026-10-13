<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= View::url('/') ?>" class="text-decoration-none">Início</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/admin/dashboard') ?>" class="text-decoration-none">Administração</a></li>
        <li class="breadcrumb-item"><a href="<?= View::url('/admin/usuarios') ?>" class="text-decoration-none">Usuários</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $usuario ? 'Editar Usuário' : 'Novo Usuário' ?></li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
            <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                <div class="feature-icon-circle bg-primary-subtle text-primary">
                    <i class="bi <?= $usuario ? 'bi-person-gear' : 'bi-person-plus-fill' ?> fs-2"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1"><?= $usuario ? 'Editar Usuário: ' . htmlspecialchars($usuario['nome']) : 'Cadastrar Novo Usuário' ?></h3>
                    <p class="text-muted small mb-0">Preencha os dados completos do usuário e configure seu perfil de acesso (RF01, RF02, RF24, RF25, RF26, RF27).</p>
                </div>
            </div>

            <form action="<?= View::url('/admin/usuarios/salvar') ?>" method="POST" novalidate>
                <?= View::csrfField() ?>
                <?php if ($usuario): ?>
                    <input type="hidden" name="id_usuario" value="<?= (int)$usuario['id_usuario'] ?>">
                <?php endif; ?>

                <!-- Dados Pessoais e Contato -->
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i> 1. Dados Pessoais & Contato</h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label for="nome" class="form-label fw-semibold">Nome Completo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" class="form-control" id="nome" name="nome" required 
                                   placeholder="Ex.: Lucas Mendes Alencar" 
                                   value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label for="telefone" class="form-label fw-semibold">Telefone / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                            <input type="text" class="form-control" id="telefone" name="telefone" 
                                   placeholder="(11) 98765-4321" 
                                   value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>">
                        </div>
                        <small class="text-muted">Para contato sobre pedidos e personalizações.</small>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-7">
                        <label for="email" class="form-label fw-semibold">E-mail de Acesso <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="usuario@sistema.com" 
                                   value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">
                        </div>
                        <small class="text-muted">Identificador único utilizado para login no sistema.</small>
                    </div>

                    <div class="col-md-5">
                        <label for="tipo_usuario" class="form-label fw-semibold">Perfil de Acesso (RBAC) <span class="text-danger">*</span></label>
                        <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                            <option value="cliente" <?= (isset($usuario) && $usuario['tipo_usuario'] === 'cliente') ? 'selected' : '' ?>>
                                🛍️ Cliente (Loja e Pedidos)
                            </option>
                            <option value="funcionario" <?= (isset($usuario) && $usuario['tipo_usuario'] === 'funcionario') ? 'selected' : '' ?>>
                                🛠️ Funcionário (Produção/Atendimento)
                            </option>
                            <option value="administrador" <?= (isset($usuario) && $usuario['tipo_usuario'] === 'administrador') ? 'selected' : '' ?>>
                                🛡️ Administrador Geral
                            </option>
                        </select>
                        <small class="text-muted">Define as permissões nas áreas do sistema.</small>
                    </div>
                </div>

                <!-- Segurança e Status -->
                <h5 class="fw-bold text-dark mb-3 pt-3 border-top"><i class="bi bi-shield-lock text-primary me-2"></i> 2. Segurança & Status da Conta</h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-7">
                        <label for="senha" class="form-label fw-semibold">
                            <?= $usuario ? 'Redefinir Senha (deixe em branco para manter)' : 'Senha Inicial <span class="text-danger">*</span>' ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" class="form-control" id="senha" name="senha" 
                                   placeholder="<?= $usuario ? 'Deixe em branco para não alterar' : 'Mínimo de 6 caracteres' ?>" 
                                   <?= !$usuario ? 'required minlength="6"' : '' ?>>
                        </div>
                        <small class="text-muted">Gravada com hash seguro BCRYPT (RNF01).</small>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold d-block">Status de Ativação (RF27)</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="ativo" name="ativo" value="1"
                                   <?= (!isset($usuario) || !empty($usuario['ativo'])) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="ativo">
                                Conta Ativa no Sistema
                            </label>
                        </div>
                        <small class="text-muted">Se desativada, o usuário é bloqueado no login.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="<?= View::url('/admin/usuarios') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold shadow-xs">
                        <i class="bi bi-check2-circle me-1"></i> <?= $usuario ? 'Salvar Alterações' : 'Cadastrar Usuário' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
