<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?= View::url('/') ?>">
            <span class="brand-icon d-flex align-items-center justify-content-center bg-primary rounded-3 text-white">
                <i class="bi bi-usb-drive-fill"></i>
            </span>
            <span>Custom<span class="text-primary">Storage</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?= View::url('/catalogo') ?>">
                        <i class="bi bi-joystick me-1"></i> Catálogo de Jogos
                    </a>
                </li>

                <?php if (Auth::check()): ?>
                    <?php if (Auth::isCliente()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/meus-pedidos') ?>">
                                <i class="bi bi-bag-check me-1"></i> Meus Pedidos
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Auth::tipo() === 'funcionario'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/funcionario/pedidos') ?>">
                                <i class="bi bi-inbox-fill me-1"></i> Pedidos Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/funcionario/personalizacoes') ?>">
                                <i class="bi bi-palette-fill me-1"></i> Personalizações
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Auth::isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/admin/dashboard') ?>">
                                <i class="bi bi-speedometer2 me-1"></i> Painel Geral
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/admin/produtos') ?>">
                                <i class="bi bi-controller me-1"></i> Produtos (Jogos)
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-sliders me-1"></i> Configurações
                            </a>
                            <ul class="dropdown-menu shadow border-0">
                                <li><a class="dropdown-item" href="<?= View::url('/admin/formatos') ?>"><i class="bi bi-tag me-2"></i>Formatos Físicos</a></li>
                                <li><a class="dropdown-item" href="<?= View::url('/admin/capacidades') ?>"><i class="bi bi-device-ssd me-2"></i>Capacidades de Memória</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/admin/pedidos') ?>">
                                <i class="bi bi-clipboard2-check me-1"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/admin/personalizacoes') ?>">
                                <i class="bi bi-palette me-1"></i> Personalizações
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/admin/usuarios') ?>">
                                <i class="bi bi-people me-1"></i> Usuários
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if (Auth::check()): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2 py-1 px-3 rounded-pill" type="button" data-bs-toggle="dropdown">
                            <span class="user-avatar-badge rounded-circle bg-primary text-white d-flex align-items-center justify-content-center">
                                <?= strtoupper(substr(Auth::nome(), 0, 1)) ?>
                            </span>
                            <span class="d-none d-sm-inline fw-semibold"><?= htmlspecialchars(Auth::nome()) ?></span>
                            <span class="badge bg-secondary text-uppercase ms-1"><?= htmlspecialchars(Auth::tipo()) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li class="px-3 py-2 text-muted border-bottom small">
                                <strong><?= htmlspecialchars(Auth::nome()) ?></strong><br>
                                <?= htmlspecialchars(Auth::email()) ?>
                            </li>
                            <?php if (Auth::isCliente()): ?>
                                <li><a class="dropdown-item py-2" href="<?= View::url('/meus-pedidos') ?>"><i class="bi bi-bag-check me-2"></i>Meus Pedidos</a></li>
                            <?php elseif (Auth::isAdmin()): ?>
                                <li><a class="dropdown-item py-2" href="<?= View::url('/admin/dashboard') ?>"><i class="bi bi-gear me-2"></i>Painel do Admin</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="<?= View::url('/logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sair da Conta
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= View::url('/login') ?>" class="btn btn-outline-light px-3 py-1 rounded-pill">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                    </a>
                    <a href="<?= View::url('/cadastro') ?>" class="btn btn-primary px-3 py-1 rounded-pill fw-semibold shadow-sm">
                        Cadastre-se
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
