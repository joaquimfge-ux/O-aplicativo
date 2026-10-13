<?php
/**
 * Controlador de Administração (UC13 a UC16, RF11, RF18 a RF27, CA12 a CA15)
 * Gerenciamento completo de Produtos (Jogos) e Usuários
 */

class AdminController {
    public function __construct() {
        Auth::requireRole('administrador');
    }

    /**
     * Dashboard Geral com KPIs
     */
    public function dashboard(): void {
        $statsPedidos = Pedido::getStats();
        $contagemUsuarios = Usuario::countByTipo();
        $totalDispositivos = count(Dispositivo::getAll(false));
        $persPendentes = Personalizacao::countPendentes();
        $pedidosRecentes = Pedido::getAll(null, null);
        $pedidosRecentes = array_slice($pedidosRecentes, 0, 5);

        View::render('admin.dashboard', [
            'title'             => 'Painel Administrativo',
            'stats'             => $statsPedidos,
            'usuarios'          => $contagemUsuarios,
            'totalDispositivos' => $totalDispositivos,
            'persPendentes'     => $persPendentes,
            'pedidosRecentes'   => $pedidosRecentes
        ]);
    }

    /**
     * Gerenciamento de Produtos / Jogos em Mídia Física (RF20 a RF23, CA12)
     */
    public function dispositivos(): void {
        $busca = $_GET['q'] ?? null;
        $genero = $_GET['genero'] ?? null;
        $plataforma = $_GET['plataforma'] ?? null;
        $formatoId = isset($_GET['formato']) && is_numeric($_GET['formato']) ? (int)$_GET['formato'] : null;

        $dispositivos = Dispositivo::getAll(false, $formatoId, $busca, $genero, $plataforma);
        $formatos = Formato::getAll();
        $generos = Dispositivo::getGeneros();
        $plataformas = Dispositivo::getPlataformas();

        View::render('admin.dispositivos', [
            'title'        => 'Gerenciar Produtos (Jogos)',
            'dispositivos' => $dispositivos,
            'formatos'     => $formatos,
            'generos'      => $generos,
            'plataformas'  => $plataformas,
            'busca'        => $busca,
            'generoSel'    => $genero,
            'plataformaSel'=> $plataforma,
            'formatoSel'   => $formatoId
        ]);
    }

    /**
     * Tela Dedicada de Cadastro e Edição de Produto (Jogo)
     */
    public function dispositivoForm(?int $id = null): void {
        $dispositivo = $id ? Dispositivo::findById($id) : null;
        if ($id && !$dispositivo) {
            View::flash('danger', 'Produto (jogo) não encontrado.');
            header('Location: ' . View::url('/admin/produtos'));
            exit;
        }

        $formatos = Formato::getAll();
        $generos = Dispositivo::getGeneros();
        $plataformas = Dispositivo::getPlataformas();

        View::render('admin.dispositivo_form', [
            'title'       => $id ? 'Editar Produto (Jogo): ' . $dispositivo['nome'] : 'Cadastro de Produto (Jogo)',
            'dispositivo' => $dispositivo,
            'formatos'    => $formatos,
            'generos'     => $generos,
            'plataformas' => $plataformas
        ]);
    }

    /**
     * Salvar Produto (Jogo) no Banco de Dados
     */
    public function salvarDispositivo(): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token de segurança expirou. Tente novamente.');
            header('Location: ' . View::url('/admin/produtos'));
            exit;
        }

        $id = !empty($_POST['id_dispositivo']) ? (int)$_POST['id_dispositivo'] : null;
        $nome = trim($_POST['nome'] ?? '');
        $idFormato = (int)($_POST['id_formato'] ?? 0);
        $preco = (float)str_replace(',', '.', $_POST['preco'] ?? 0);

        if (empty($nome) || $idFormato <= 0 || $preco <= 0) {
            View::flash('danger', 'Por favor, informe o título do jogo, o formato de mídia e um preço base válido.');
            $redirect = $id ? "/admin/produtos/editar/{$id}" : '/admin/produtos/novo';
            header('Location: ' . View::url($redirect));
            exit;
        }

        $data = [
            'nome'           => $nome,
            'genero'         => trim($_POST['genero'] ?? 'Aventura'),
            'plataforma'     => trim($_POST['plataforma'] ?? 'Super Nintendo (SNES)'),
            'desenvolvedora' => trim($_POST['desenvolvedora'] ?? ''),
            'ano_lancamento' => !empty($_POST['ano_lancamento']) ? (int)$_POST['ano_lancamento'] : null,
            'id_formato'     => $idFormato,
            'preco'          => $preco,
            'descricao'      => trim($_POST['descricao'] ?? ''),
            'imagem'         => trim($_POST['imagem'] ?? 'assets/images/dispositivo_cartucho.svg'),
            'ativo'          => isset($_POST['ativo']) ? 1 : 0
        ];

        try {
            if ($id) {
                Dispositivo::update($id, $data);
                View::flash('success', "Jogo '{$nome}' atualizado com sucesso!");
            } else {
                $newId = Dispositivo::create($data);
                View::flash('success', "Produto/Jogo '{$nome}' cadastrado com sucesso!");
            }
        } catch (Exception $e) {
            View::flash('danger', 'Erro ao salvar produto: ' . $e->getMessage());
        }

        header('Location: ' . View::url('/admin/produtos'));
        exit;
    }

    /**
     * Remover Produto (Jogo)
     */
    public function removerDispositivo(int $id): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token inválido.');
            header('Location: ' . View::url('/admin/produtos'));
            exit;
        }

        try {
            Dispositivo::delete($id);
            View::flash('success', 'Produto (jogo) removido ou inativado com sucesso.');
        } catch (Exception $e) {
            View::flash('danger', 'Erro ao remover: ' . $e->getMessage());
        }

        header('Location: ' . View::url('/admin/produtos'));
        exit;
    }

    /**
     * Gerenciamento de Formatos Físicos
     */
    public function formatos(): void {
        $formatos = Formato::getAll();
        View::render('admin.formatos', [
            'title'    => 'Gerenciar Formatos Físicos',
            'formatos' => $formatos
        ]);
    }

    public function salvarFormato(): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token inválido.');
            header('Location: ' . View::url('/admin/formatos'));
            exit;
        }

        $id = !empty($_POST['id_formato']) ? (int)$_POST['id_formato'] : null;
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $icone = trim($_POST['icone'] ?? 'bi-hdd');

        if (empty($nome)) {
            View::flash('danger', 'O nome do formato é obrigatório.');
            header('Location: ' . View::url('/admin/formatos'));
            exit;
        }

        try {
            if ($id) {
                Formato::update($id, $nome, $descricao, $icone);
                View::flash('success', "Formato '{$nome}' atualizado.");
            } else {
                Formato::create($nome, $descricao, $icone);
                View::flash('success', "Novo formato '{$nome}' adicionado.");
            }
        } catch (Exception $e) {
            View::flash('danger', 'Erro ao salvar formato: ' . $e->getMessage());
        }

        header('Location: ' . View::url('/admin/formatos'));
        exit;
    }

    public function removerFormato(int $id): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token inválido.');
            header('Location: ' . View::url('/admin/formatos'));
            exit;
        }

        try {
            Formato::delete($id);
            View::flash('success', 'Formato removido com sucesso.');
        } catch (Exception $e) {
            View::flash('danger', $e->getMessage());
        }

        header('Location: ' . View::url('/admin/formatos'));
        exit;
    }

    /**
     * Gerenciamento de Capacidades
     */
    public function capacidades(): void {
        $capacidades = Capacidade::getAll();
        View::render('admin.capacidades', [
            'title'       => 'Gerenciar Opções de Capacidade de Memória',
            'capacidades' => $capacidades
        ]);
    }

    public function salvarCapacidade(): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token inválido.');
            header('Location: ' . View::url('/admin/capacidades'));
            exit;
        }

        $id = !empty($_POST['id_capacidade']) ? (int)$_POST['id_capacidade'] : null;
        $capacidadeGb = (int)($_POST['capacidade_gb'] ?? 0);
        $precoAdicional = (float)str_replace(',', '.', $_POST['preco_adicional'] ?? 0);

        if ($capacidadeGb <= 0) {
            View::flash('danger', 'Informe uma capacidade em GB válida.');
            header('Location: ' . View::url('/admin/capacidades'));
            exit;
        }

        try {
            if ($id) {
                Capacidade::update($id, $capacidadeGb, $precoAdicional);
                View::flash('success', "Capacidade de {$capacidadeGb} GB atualizada.");
            } else {
                Capacidade::create($capacidadeGb, $precoAdicional);
                View::flash('success', "Capacidade de {$capacidadeGb} GB cadastrada com sucesso.");
            }
        } catch (Exception $e) {
            View::flash('danger', 'Erro ao salvar capacidade: ' . $e->getMessage());
        }

        header('Location: ' . View::url('/admin/capacidades'));
        exit;
    }

    public function removerCapacidade(int $id): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token inválido.');
            header('Location: ' . View::url('/admin/capacidades'));
            exit;
        }

        try {
            Capacidade::delete($id);
            View::flash('success', 'Capacidade removida com sucesso.');
        } catch (Exception $e) {
            View::flash('danger', $e->getMessage());
        }

        header('Location: ' . View::url('/admin/capacidades'));
        exit;
    }

    /**
     * Consulta de Usuários (RF24 a RF27, CA13)
     */
    public function usuarios(): void {
        $tipo = $_GET['tipo'] ?? null;
        $busca = $_GET['q'] ?? null;

        $usuarios = Usuario::getAll($tipo, $busca);
        $contagem = Usuario::countByTipo();

        View::render('admin.usuarios', [
            'title'    => 'Gerenciamento de Usuários e Acessos',
            'usuarios' => $usuarios,
            'contagem' => $contagem,
            'filtroTipo' => $tipo,
            'busca'    => $busca
        ]);
    }

    /**
     * Tela Dedicada de Cadastro e Edição de Usuário
     */
    public function usuarioForm(?int $id = null): void {
        $usuario = $id ? Usuario::findById($id) : null;
        if ($id && !$usuario) {
            View::flash('danger', 'Usuário não encontrado.');
            header('Location: ' . View::url('/admin/usuarios'));
            exit;
        }

        View::render('admin.usuario_form', [
            'title'   => $id ? 'Editar Usuário: ' . $usuario['nome'] : 'Cadastro de Novo Usuário',
            'usuario' => $usuario
        ]);
    }

    /**
     * Salvar Usuário (RF25, RF26)
     */
    public function salvarUsuario(): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token de formulário expirou.');
            header('Location: ' . View::url('/admin/usuarios'));
            exit;
        }

        $id = !empty($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : null;
        $nome = trim($_POST['nome'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $telefone = trim($_POST['telefone'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        $tipo = $_POST['tipo_usuario'] ?? 'cliente';
        $ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;

        if (empty($nome) || empty($email)) {
            View::flash('danger', 'Nome e e-mail são obrigatórios.');
            $redirect = $id ? "/admin/usuarios/editar/{$id}" : '/admin/usuarios/novo';
            header('Location: ' . View::url($redirect));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            View::flash('danger', 'Informe um e-mail válido.');
            $redirect = $id ? "/admin/usuarios/editar/{$id}" : '/admin/usuarios/novo';
            header('Location: ' . View::url($redirect));
            exit;
        }

        // Verifica duplicidade de e-mail
        $existente = Usuario::findByEmail($email);
        if ($existente && (!$id || $existente['id_usuario'] !== $id)) {
            View::flash('danger', 'Já existe um usuário cadastrado com este e-mail.');
            $redirect = $id ? "/admin/usuarios/editar/{$id}" : '/admin/usuarios/novo';
            header('Location: ' . View::url($redirect));
            exit;
        }

        try {
            if ($id) {
                // Edição de usuário existente (RF26)
                Usuario::update(
                    $id, 
                    $nome, 
                    $email, 
                    !empty($senha) ? $senha : null, 
                    $tipo, 
                    $ativo, 
                    $telefone
                );
                View::flash('success', "Informações do usuário '{$nome}' atualizadas com sucesso.");
            } else {
                // Cadastro de novo usuário (RF25)
                if (empty($senha) || strlen($senha) < 6) {
                    View::flash('danger', 'A senha é obrigatória para novo cadastro e deve ter no mínimo 6 caracteres.');
                    header('Location: ' . View::url('/admin/usuarios/novo'));
                    exit;
                }
                Usuario::create($nome, $email, $senha, $tipo, $ativo, $telefone);
                View::flash('success', "Usuário '{$nome}' ({$tipo}) cadastrado com sucesso.");
            }
        } catch (Exception $e) {
            View::flash('danger', 'Erro ao salvar usuário: ' . $e->getMessage());
        }

        header('Location: ' . View::url('/admin/usuarios'));
        exit;
    }

    /**
     * Desativar / Reativar conta de funcionário ou usuário (RF27, CA13)
     */
    public function toggleStatusUsuario(int $id): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token inválido.');
            header('Location: ' . View::url('/admin/usuarios'));
            exit;
        }

        if ($id === Auth::id()) {
            View::flash('danger', 'Você não pode desativar sua própria conta de administrador.');
            header('Location: ' . View::url('/admin/usuarios'));
            exit;
        }

        $usuario = Usuario::findById($id);
        if (!$usuario) {
            View::flash('danger', 'Usuário não encontrado.');
            header('Location: ' . View::url('/admin/usuarios'));
            exit;
        }

        $novoStatus = $usuario['ativo'] ? 0 : 1;
        Usuario::toggleAtivo($id, $novoStatus);

        $msg = $novoStatus ? "Conta de '{$usuario['nome']}' reativada com sucesso." : "Conta de '{$usuario['nome']}' desativada com sucesso.";
        View::flash('success', $msg);

        header('Location: ' . View::url('/admin/usuarios'));
        exit;
    }

    /**
     * Gerenciamento de Pedidos pelo Admin (RF18, RF19, CA14)
     */
    public function pedidos(): void {
        $status = $_GET['status'] ?? null;
        $busca  = $_GET['q'] ?? null;

        $pedidos = Pedido::getAll($status, $busca);
        $stats = Pedido::getStats();

        View::render('admin.pedidos', [
            'title'        => 'Gerenciamento de Pedidos - Admin',
            'pedidos'      => $pedidos,
            'filtroStatus' => $status,
            'busca'        => $busca,
            'stats'        => $stats
        ]);
    }

    /**
     * Gerenciamento de Personalizações pelo Admin (RF11, CA15)
     */
    public function personalizacoes(): void {
        $status = $_GET['status'] ?? null;
        $personalizacoes = Personalizacao::getAllWithPedido($status);

        View::render('admin.personalizacoes', [
            'title'           => 'Gerenciamento de Personalizações - Admin',
            'personalizacoes' => $personalizacoes,
            'filtroStatus'    => $status
        ]);
    }
}
