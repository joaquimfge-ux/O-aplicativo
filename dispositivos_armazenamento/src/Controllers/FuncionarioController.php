<?php
/**
 * Controlador da Área de Funcionários (UC09 a UC12, RF10, RF15, RF16, RF17, CA08, CA11)
 */

class FuncionarioController {
    public function __construct() {
        Auth::requireRole(['funcionario', 'administrador']);
    }

    /**
     * Lista de pedidos com filtros de status e busca (RF15)
     */
    public function pedidos(): void {
        $status = $_GET['status'] ?? null;
        $busca  = $_GET['q'] ?? null;

        $pedidos = Pedido::getAll($status, $busca);
        $stats = Pedido::getStats();

        View::render('funcionario.pedidos', [
            'title'        => 'Painel de Pedidos - Funcionário',
            'pedidos'      => $pedidos,
            'filtroStatus' => $status,
            'busca'        => $busca,
            'stats'        => $stats
        ]);
    }

    /**
     * Detalhes completos do pedido (RF16)
     */
    public function pedidoDetalhes(int $id): void {
        $pedido = Pedido::findById($id);
        if (!$pedido) {
            View::flash('danger', 'Pedido não encontrado.');
            header('Location: ' . View::url('/funcionario/pedidos'));
            exit;
        }

        View::render('funcionario.pedido_detalhes', [
            'title'  => "Gerenciar Pedido #{$pedido['id_pedido']}",
            'pedido' => $pedido
        ]);
    }

    /**
     * Atualização de status do pedido (RF17, CA11)
     */
    public function atualizarStatusPedido(): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token de formulário expirado.');
            header('Location: ' . View::url('/funcionario/pedidos'));
            exit;
        }

        $idPedido = (int)($_POST['id_pedido'] ?? 0);
        $novoStatus = trim($_POST['status'] ?? '');

        try {
            Pedido::updateStatus($idPedido, $novoStatus);
            View::flash('success', "Status do pedido #{$idPedido} atualizado para '{$novoStatus}'.");
        } catch (Exception $e) {
            View::flash('danger', 'Erro ao alterar status: ' . $e->getMessage());
        }

        $redirect = $_POST['redirect'] ?? ('/funcionario/pedidos/' . $idPedido);
        header('Location: ' . View::url($redirect));
        exit;
    }

    /**
     * Consulta e acompanhamento de solicitações de personalização (RF10, CA08)
     */
    public function personalizacoes(): void {
        $status = $_GET['status'] ?? null;
        $personalizacoes = Personalizacao::getAllWithPedido($status);

        View::render('funcionario.personalizacoes', [
            'title'           => 'Acompanhamento de Personalizações',
            'personalizacoes' => $personalizacoes,
            'filtroStatus'    => $status
        ]);
    }

    /**
     * Atualiza o status da personalização
     */
    public function atualizarStatusPersonalizacao(): void {
        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token de formulário expirado.');
            header('Location: ' . View::url('/funcionario/personalizacoes'));
            exit;
        }

        $idPers = (int)($_POST['id_personalizacao'] ?? 0);
        $novoStatus = trim($_POST['status'] ?? '');

        try {
            Personalizacao::updateStatus($idPers, $novoStatus);
            View::flash('success', "Status da personalização atualizado para '{$novoStatus}'.");
        } catch (Exception $e) {
            View::flash('danger', 'Erro ao atualizar personalização: ' . $e->getMessage());
        }

        header('Location: ' . View::url('/funcionario/personalizacoes'));
        exit;
    }
}
