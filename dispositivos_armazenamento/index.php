<?php
/**
 * Ponto de Entrada Principal (Front Controller / Router)
 * Sistema de Dispositivos de Armazenamento Personalizados
 */

require_once __DIR__ . '/config/config.php';

// Obtém o caminho da rota requisitada
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Remove o caminho base (BASE_PATH) para obter a rota relativa limpa
$basePath = rtrim(BASE_PATH, '/');
if ($basePath !== '' && strpos($requestPath, $basePath) === 0) {
    $route = substr($requestPath, strlen($basePath));
} else {
    $route = $requestPath;
}

// Suporte adicional para parâmetro 'r' (ex.: index.php?r=catalogo)
if (isset($_GET['r']) && trim($_GET['r']) !== '') {
    $route = '/' . ltrim($_GET['r'], '/');
}

$route = '/' . trim($route, '/');
if ($route === '/index.php' || $route === '') {
    $route = '/';
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    // Roteamento Geral
    switch (true) {
        // Home / Catálogo
        case $route === '/' || $route === '/catalogo':
            (new ClienteController())->catalogo();
            break;

        // Autenticação (RF01, RF02, CA01, CA02)
        case $route === '/login' && $method === 'GET':
            (new AuthController())->showLogin();
            break;

        case $route === '/login' && $method === 'POST':
            (new AuthController())->login();
            break;

        case $route === '/cadastro' && $method === 'GET':
            (new AuthController())->showRegister();
            break;

        case $route === '/cadastro' && $method === 'POST':
            (new AuthController())->register();
            break;

        case $route === '/logout':
            (new AuthController())->logout();
            break;

        // Cliente: Dispositivos & Pedidos (UC04 a UC08, RF05 a RF14)
        case preg_match('#^/dispositivo/(\d+)$#', $route, $matches) && $method === 'GET':
            (new ClienteController())->dispositivoDetalhes((int)$matches[1]);
            break;

        case preg_match('#^/pedido/configurar/(\d+)$#', $route, $matches) && $method === 'GET':
            (new ClienteController())->configurarPedido((int)$matches[1]);
            break;

        case $route === '/pedido/salvar' && $method === 'POST':
            (new ClienteController())->salvarPedido();
            break;

        case $route === '/meus-pedidos' && $method === 'GET':
            (new ClienteController())->meusPedidos();
            break;

        case preg_match('#^/meus-pedidos/(\d+)$#', $route, $matches) && $method === 'GET':
            (new ClienteController())->pedidoDetalhes((int)$matches[1]);
            break;

        // Funcionário: Pedidos & Personalizações (UC09 a UC12, RF10, RF15, RF16, RF17)
        case $route === '/funcionario/pedidos' && $method === 'GET':
            (new FuncionarioController())->pedidos();
            break;

        case preg_match('#^/funcionario/pedidos/(\d+)$#', $route, $matches) && $method === 'GET':
            (new FuncionarioController())->pedidoDetalhes((int)$matches[1]);
            break;

        case $route === '/funcionario/pedidos/atualizar-status' && $method === 'POST':
            (new FuncionarioController())->atualizarStatusPedido();
            break;

        case $route === '/funcionario/personalizacoes' && $method === 'GET':
            (new FuncionarioController())->personalizacoes();
            break;

        case $route === '/funcionario/personalizacoes/atualizar-status' && $method === 'POST':
            (new FuncionarioController())->atualizarStatusPersonalizacao();
            break;

        // Administrador: Dashboard e Gestão Geral (UC13 a UC16, RF11, RF18 a RF27)
        case $route === '/admin/dashboard' && $method === 'GET':
            (new AdminController())->dashboard();
            break;

        // Dispositivos (RF20 a RF23, CA12)
        // Produtos / Jogos (RF20 a RF23, CA12)
        case ($route === '/admin/dispositivos' || $route === '/admin/produtos') && $method === 'GET':
            (new AdminController())->dispositivos();
            break;

        case ($route === '/admin/dispositivos/novo' || $route === '/admin/produtos/novo') && $method === 'GET':
            (new AdminController())->dispositivoForm(null);
            break;

        case preg_match('#^/admin/(?:dispositivos|produtos)/editar/(\d+)$#', $route, $matches) && $method === 'GET':
            (new AdminController())->dispositivoForm((int)$matches[1]);
            break;

        case ($route === '/admin/dispositivos/salvar' || $route === '/admin/produtos/salvar') && $method === 'POST':
            (new AdminController())->salvarDispositivo();
            break;

        case preg_match('#^/admin/(?:dispositivos|produtos)/remover/(\d+)$#', $route, $matches) && $method === 'POST':
            (new AdminController())->removerDispositivo((int)$matches[1]);
            break;

        // Formatos
        case $route === '/admin/formatos' && $method === 'GET':
            (new AdminController())->formatos();
            break;

        case $route === '/admin/formatos/salvar' && $method === 'POST':
            (new AdminController())->salvarFormato();
            break;

        case preg_match('#^/admin/formatos/remover/(\d+)$#', $route, $matches) && $method === 'POST':
            (new AdminController())->removerFormato((int)$matches[1]);
            break;

        // Capacidades
        case $route === '/admin/capacidades' && $method === 'GET':
            (new AdminController())->capacidades();
            break;

        case $route === '/admin/capacidades/salvar' && $method === 'POST':
            (new AdminController())->salvarCapacidade();
            break;

        case preg_match('#^/admin/capacidades/remover/(\d+)$#', $route, $matches) && $method === 'POST':
            (new AdminController())->removerCapacidade((int)$matches[1]);
            break;

        // Usuários e Funcionários (RF24 a RF27, CA13)
        case $route === '/admin/usuarios' && $method === 'GET':
            (new AdminController())->usuarios();
            break;

        case $route === '/admin/usuarios/novo' && $method === 'GET':
            (new AdminController())->usuarioForm(null);
            break;

        case preg_match('#^/admin/usuarios/editar/(\d+)$#', $route, $matches) && $method === 'GET':
            (new AdminController())->usuarioForm((int)$matches[1]);
            break;

        case $route === '/admin/usuarios/salvar' && $method === 'POST':
            (new AdminController())->salvarUsuario();
            break;

        case preg_match('#^/admin/usuarios/toggle/(\d+)$#', $route, $matches) && $method === 'POST':
            (new AdminController())->toggleStatusUsuario((int)$matches[1]);
            break;

        // Pedidos & Personalizações no Admin
        case $route === '/admin/pedidos' && $method === 'GET':
            (new AdminController())->pedidos();
            break;

        case $route === '/admin/personalizacoes' && $method === 'GET':
            (new AdminController())->personalizacoes();
            break;

        default:
            http_response_code(404);
            View::render('cliente.catalogo', [
                'title'         => 'Página Não Encontrada - Catálogo',
                'dispositivos'  => Dispositivo::getAll(true),
                'formatos'      => Formato::getAll(),
                'filtroFormato' => null,
                'busca'         => null
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo '<div style="font-family:sans-serif; max-width:600px; margin:50px auto; padding:20px; border:1px solid #f87171; background:#fee2e2; border-radius:8px;">';
    echo '<h3 style="color:#b91c1c; margin-top:0;">Erro no Sistema</h3>';
    echo '<p style="color:#7f1d1d;">' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<a href="' . View::url('/') . '" style="color:#1d4ed8;">Voltar para o início</a>';
    echo '</div>';
}
