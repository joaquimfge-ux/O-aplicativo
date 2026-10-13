<?php
/**
 * Controlador de Autenticação e Registro (RF01, RF02, RF03, CA01, CA02, CA03, RNF01, RNF02)
 */

class AuthController {
    public function showLogin(): void {
        if (Auth::check()) {
            $this->redirectByRole();
            return;
        }
        View::render('auth.login', ['title' => 'Entrar no Sistema']);
    }

    public function login(): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token de segurança inválido. Recarregue a página.');
            header('Location: ' . View::url('/login'));
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        // Validação no servidor (RNF02)
        if (empty($email) || empty($senha)) {
            View::flash('danger', 'Preencha o e-mail e a senha.');
            header('Location: ' . View::url('/login'));
            exit;
        }

        $usuario = Usuario::findByEmail($email);

        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            View::flash('danger', 'E-mail ou senha incorretos.');
            header('Location: ' . View::url('/login'));
            exit;
        }

        // Verifica se a conta está desativada (RF27)
        if (empty($usuario['ativo'])) {
            View::flash('danger', 'Esta conta de usuário foi desativada pelo administrador.');
            header('Location: ' . View::url('/login'));
            exit;
        }

        Auth::login($usuario);
        View::flash('success', "Bem-vindo de volta, {$usuario['nome']}!");
        $this->redirectByRole();
    }

    public function showRegister(): void {
        if (Auth::check()) {
            $this->redirectByRole();
            return;
        }
        View::render('auth.cadastro', ['title' => 'Criar Conta de Usuário']);
    }

    public function register(): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token de segurança inválido. Recarregue a página.');
            header('Location: ' . View::url('/cadastro'));
            exit;
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        $confirmar = trim($_POST['confirmar_senha'] ?? '');

        // Validação no servidor (RNF02, CA01)
        if (empty($nome) || empty($email) || empty($senha)) {
            View::flash('danger', 'Nome, e-mail e senha são obrigatórios.');
            header('Location: ' . View::url('/cadastro'));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            View::flash('danger', 'Informe um e-mail válido.');
            header('Location: ' . View::url('/cadastro'));
            exit;
        }

        if (strlen($senha) < 6) {
            View::flash('danger', 'A senha deve possuir no mínimo 6 caracteres.');
            header('Location: ' . View::url('/cadastro'));
            exit;
        }

        if ($senha !== $confirmar) {
            View::flash('danger', 'As senhas não coincidem.');
            header('Location: ' . View::url('/cadastro'));
            exit;
        }

        if (Usuario::findByEmail($email)) {
            View::flash('danger', 'Já existe um cadastro com este e-mail.');
            header('Location: ' . View::url('/cadastro'));
            exit;
        }

        $userId = Usuario::create($nome, $email, $senha, 'cliente', 1, $telefone);
        $user = Usuario::findById($userId);

        Auth::login($user);
        View::flash('success', 'Conta criada com sucesso! Seja bem-vindo à nossa loja de jogos e dispositivos.');
        header('Location: ' . View::url('/catalogo'));
        exit;
    }

    public function logout(): void {
        Auth::logout();
        View::flash('info', 'Você saiu da sua conta.');
        header('Location: ' . View::url('/login'));
        exit;
    }

    private function redirectByRole(): void {
        $tipo = Auth::tipo();
        if ($tipo === 'administrador') {
            header('Location: ' . View::url('/admin/dashboard'));
        } elseif ($tipo === 'funcionario') {
            header('Location: ' . View::url('/funcionario/pedidos'));
        } else {
            header('Location: ' . View::url('/catalogo'));
        }
        exit;
    }
}
