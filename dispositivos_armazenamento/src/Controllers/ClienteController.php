<?php
/**
 * Controlador de Ações do Cliente (UC01 a UC08, RF04 a RF09, RF12 a RF14)
 */

class ClienteController {
    /**
     * Vitrine/Catálogo de Dispositivos (RF04, CA04, CA05)
     */
    public function catalogo(): void {
        $formatoId = isset($_GET['formato']) && is_numeric($_GET['formato']) ? (int)$_GET['formato'] : null;
        $busca = $_GET['q'] ?? null;
        $genero = $_GET['genero'] ?? null;
        $plataforma = $_GET['plataforma'] ?? null;

        $dispositivos = Dispositivo::getAll(true, $formatoId, $busca, $genero, $plataforma);
        $formatos = Formato::getAll();
        $generos = Dispositivo::getGeneros();
        $plataformas = Dispositivo::getPlataformas();

        View::render('cliente.catalogo', [
            'title'           => 'Catálogo de Produtos (Jogos em Mídia Física)',
            'dispositivos'    => $dispositivos,
            'formatos'        => $formatos,
            'generos'         => $generos,
            'plataformas'     => $plataformas,
            'filtroFormato'   => $formatoId,
            'filtroGenero'    => $genero,
            'filtroPlataforma'=> $plataforma,
            'busca'           => $busca
        ]);
    }

    /**
     * Detalhes do Dispositivo e Seleção de Capacidade (RF05, RF06, CA06)
     */
    public function dispositivoDetalhes(int $id): void {
        $dispositivo = Dispositivo::findById($id);
        if (!$dispositivo || !$dispositivo['ativo']) {
            View::flash('warning', 'Dispositivo não encontrado ou indisponível.');
            header('Location: ' . View::url('/catalogo'));
            exit;
        }

        $capacidades = Capacidade::getAll();

        View::render('cliente.dispositivo_detalhes', [
            'title'       => $dispositivo['nome'],
            'dispositivo' => $dispositivo,
            'capacidades' => $capacidades
        ]);
    }

    /**
     * Tela de Configuração do Pedido / Personalização (RF07, RF08, RF09, CA06, CA07)
     */
    public function configurarPedido(int $id): void {
        Auth::requireAuth(); // CA03: exige autenticação

        $dispositivo = Dispositivo::findById($id);
        if (!$dispositivo || !$dispositivo['ativo']) {
            View::flash('warning', 'Dispositivo não encontrado ou indisponível.');
            header('Location: ' . View::url('/catalogo'));
            exit;
        }

        $capacidades = Capacidade::getAll();
        $tipoPedido = $_GET['tipo'] ?? 'pronto'; // 'pronto' ou 'personalizado'

        View::render('cliente.novo_pedido', [
            'title'         => 'Montar Pedido - ' . $dispositivo['nome'],
            'dispositivo'   => $dispositivo,
            'capacidades'   => $capacidades,
            'personalizado' => ($tipoPedido === 'personalizado')
        ]);
    }

    /**
     * Salvar Pedido e Personalização (RF12, RF13, CA07, CA09)
     */
    public function salvarPedido(): void {
        Auth::requireAuth();

        if (!View::verifyCsrf()) {
            View::flash('danger', 'Token de segurança expirou. Tente novamente.');
            header('Location: ' . View::url('/catalogo'));
            exit;
        }

        $idDispositivo = (int)($_POST['id_dispositivo'] ?? 0);
        $idCapacidade  = (int)($_POST['id_capacidade'] ?? 0);
        $tipoPedido    = $_POST['tipo_pedido'] ?? 'pronto';
        $temPers       = ($tipoPedido === 'personalizado');

        $dadosPers = [];
        if ($temPers) {
            $dadosPers = [
                'descricao'      => trim($_POST['descricao_personalizacao'] ?? ''),
                'tipo_conteudo'  => trim($_POST['tipo_conteudo'] ?? 'Memórias Pessoais'),
                'texto_gravacao' => trim($_POST['texto_gravacao'] ?? ''),
                'cor_acabamento' => trim($_POST['cor_acabamento'] ?? 'Padrão')
            ];

            if (empty($dadosPers['descricao'])) {
                View::flash('danger', 'Informe as características desejadas para a personalização do dispositivo.');
                header('Location: ' . View::url('/pedido/configurar/' . $idDispositivo . '?tipo=personalizado'));
                exit;
            }
        }

        try {
            $idPedido = Pedido::create(
                Auth::id(),
                $idDispositivo,
                $idCapacidade,
                $temPers,
                $dadosPers
            );

            View::flash('success', "Pedido #{$idPedido} realizado com sucesso! Nossa equipe já está processando sua solicitação.");
            header('Location: ' . View::url('/meus-pedidos/' . $idPedido));
            exit;

        } catch (Exception $e) {
            View::flash('danger', 'Erro ao processar pedido: ' . $e->getMessage());
            header('Location: ' . View::url('/pedido/configurar/' . $idDispositivo));
            exit;
        }
    }

    /**
     * Meus Pedidos (RF14, CA10, RNF08)
     */
    public function meusPedidos(): void {
        Auth::requireAuth();
        $pedidos = Pedido::getByUsuario(Auth::id());

        View::render('cliente.meus_pedidos', [
            'title'   => 'Meus Pedidos',
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Detalhes do Pedido do Cliente (RF14, RNF08)
     */
    public function pedidoDetalhes(int $id): void {
        Auth::requireAuth();
        $pedido = Pedido::findById($id);

        // Verifica posse do pedido para privacidade (RNF08)
        if (!$pedido || ($pedido['id_usuario'] !== Auth::id() && !Auth::isFuncionario())) {
            View::flash('danger', 'Pedido não encontrado ou acesso não autorizado.');
            header('Location: ' . View::url('/meus-pedidos'));
            exit;
        }

        View::render('cliente.pedido_detalhes', [
            'title'  => "Detalhes do Pedido #{$pedido['id_pedido']}",
            'pedido' => $pedido
        ]);
    }
}
