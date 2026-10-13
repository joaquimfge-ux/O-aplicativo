<?php
/**
 * Script de inicialização automática do Banco de Dados
 * Cria o banco 'dispositivos_db', as tabelas e dados iniciais (Seed).
 */

require_once __DIR__ . '/../config/database.php';

try {
    echo "====================================================\n";
    echo "Inicializando Banco de Dados - Dispositivos Personalizados\n";
    echo "====================================================\n";

    // 1. Conexão inicial com o MySQL (sem selecionar banco de dados)
    $host = DB_HOST;
    $port = DB_PORT;
    $user = DB_USER;
    $pass = DB_PASS;
    $dbname = DB_NAME;

    $pdoRoot = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Cria banco se não existir
    $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "[OK] Banco de dados `{$dbname}` verificado/criado.\n";

    // Conecta no banco recém-criado
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Executa Schema
    $schemaFile = __DIR__ . '/schema.sql';
    if (!file_exists($schemaFile)) {
        throw new Exception("Arquivo schema.sql não encontrado!");
    }
    $schemaSql = file_get_contents($schemaFile);
    $pdo->exec($schemaSql);
    echo "[OK] Tabelas criadas com sucesso conforme especificação.\n";

    // 2. Inserção de Dados Iniciais (Seed)
    echo "Inserindo dados iniciais (Seed)...\n";

    // Senhas com BCRYPT seguro (RNF01)
    $hashAdmin = password_hash('admin123', PASSWORD_DEFAULT);
    $hashFunc  = password_hash('func123', PASSWORD_DEFAULT);
    $hashCli   = password_hash('cliente123', PASSWORD_DEFAULT);
    $hashCli2  = password_hash('cliente123', PASSWORD_DEFAULT);

    // Usuários
    $stmtUser = $pdo->prepare("INSERT INTO usuario (nome, email, senha, tipo_usuario, ativo) VALUES (?, ?, ?, ?, ?)");
    $stmtUser->execute(['Administrador Geral', 'admin@sistema.com', $hashAdmin, 'administrador', 1]);
    $adminId = $pdo->lastInsertId();

    $stmtUser->execute(['Carlos Eduardo (Funcionário)', 'funcionario@sistema.com', $hashFunc, 'funcionario', 1]);
    $funcId = $pdo->lastInsertId();

    $stmtUser->execute(['Mariana Silva (Cliente)', 'cliente@sistema.com', $hashCli, 'cliente', 1]);
    $cli1Id = $pdo->lastInsertId();

    $stmtUser->execute(['Lucas Oliveira (Cliente)', 'lucas@cliente.com', $hashCli2, 'cliente', 1]);
    $cli2Id = $pdo->lastInsertId();

    echo "[OK] Usuários inseridos: admin@sistema.com, funcionario@sistema.com, cliente@sistema.com\n";

    // Formatos
    $formatos = [
        ['Cartucho Retrô', 'Réplica nostálgica de cartuchos clássicos de videogame com conexão USB 3.2.', 'bi-controller'],
        ['Fita Cassete Vintage', 'Chassi retrô de fita cassete com USB retrátil e estojo colecionável.', 'bi-cassette'],
        ['Madeira Maciça Artesanal', 'Corpo esculpido em nogueira e freixo com fechamento magnético e toque artesanal.', 'bi-tree'],
        ['Mini SSD Blindado', 'Construção tática em liga de alumínio aeroespacial com resistência a choques.', 'bi-shield-check'],
        ['Cristal com LED Holográfico', 'Bloco de vidro óptico/acrílico com gravação interna iluminada por LED.', 'bi-gem']
    ];

    $stmtFormat = $pdo->prepare("INSERT INTO formato (nome, descricao, icone) VALUES (?, ?, ?)");
    $formatoIds = [];
    foreach ($formatos as $f) {
        $stmtFormat->execute($f);
        $formatoIds[$f[0]] = $pdo->lastInsertId();
    }
    echo "[OK] 5 formatos cadastrados.\n";

    // Capacidades
    $capacidades = [
        [32, 0.00],
        [64, 35.00],
        [128, 69.00],
        [256, 139.00],
        [512, 249.00],
        [1024, 429.00]
    ];
    $stmtCap = $pdo->prepare("INSERT INTO capacidade (capacidade_gb, preco_adicional) VALUES (?, ?)");
    $capIds = [];
    foreach ($capacidades as $c) {
        $stmtCap->execute($c);
        $capIds[$c[0]] = $pdo->lastInsertId();
    }
    echo "[OK] 6 capacidades cadastradas (32GB até 1TB).\n";

    // Dispositivos
    $dispositivos = [
        [
            $formatoIds['Cartucho Retrô'],
            'Cartucho Retrô SNES 16-Bit',
            'Dispositivo com design fiel ao Super Nintendo. Ideal para armazenar ROMs, jogos clássicos e emuladores com boot rápido.',
            149.90,
            'assets/images/dispositivo_cartucho.svg'
        ],
        [
            $formatoIds['Cartucho Retrô'],
            'Cartucho Mini Arcade NES 8-Bit',
            'Visual nostálgico cinza clássico com conector reforçado USB 3.0 para colecionadores de games retrô.',
            139.90,
            'assets/images/dispositivo_cartucho_nes.svg'
        ],
        [
            $formatoIds['Fita Cassete Vintage'],
            'Mixtape Memories Fita K7',
            'Formato autêntico de fita cassete dos anos 80/90. Perfeito para guardar trilhas sonoras, vídeos de viagens e recordações.',
            129.90,
            'assets/images/dispositivo_k7.svg'
        ],
        [
            $formatoIds['Madeira Maciça Artesanal'],
            'Pendrive EcoWood Nogueira com Tampa Magnética',
            'Acabamento premium em madeira nobre, ideal para ensaios fotográficos de casamento, batizados e portfólios elegantes.',
            99.90,
            'assets/images/dispositivo_wood.svg'
        ],
        [
            $formatoIds['Mini SSD Blindado'],
            'Mini SSD CyberVault Rugged NVMe',
            'Dispositivo ultra-rápido de alta durabilidade com dissipador térmico em alumínio. Para criadores de conteúdo e videomakers.',
            289.90,
            'assets/images/dispositivo_ssd.svg'
        ],
        [
            $formatoIds['Cristal com LED Holográfico'],
            'Lumina Glass Cristal 3D com LED',
            'Bloco de cristal polido com micro-gravação interna e LED azul que acende ao conectar na porta USB.',
            179.90,
            'assets/images/dispositivo_crystal.svg'
        ]
    ];

    $stmtDisp = $pdo->prepare("INSERT INTO dispositivo (id_formato, nome, descricao, preco, imagem) VALUES (?, ?, ?, ?, ?)");
    $dispIds = [];
    foreach ($dispositivos as $idx => $d) {
        $stmtDisp->execute($d);
        $dispIds[] = $pdo->lastInsertId();
    }
    echo "[OK] 6 dispositivos cadastrados.\n";

    // Pedidos e Personalizações Iniciais de Demonstração
    // Pedido 1: Modelo Pronto (sem personalização) para Mariana Silva
    // Cartucho Retrô SNES (149.90) + 64GB (+35.00) = 184.90
    $stmtPed = $pdo->prepare("INSERT INTO pedido (id_usuario, id_dispositivo, id_capacidade, status, valor_total, data_pedido) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtPed->execute([$cli1Id, $dispIds[0], $capIds[64], 'Concluído', 184.90, date('Y-m-d H:i:s', strtotime('-5 days'))]);
    $ped1Id = $pdo->lastInsertId();

    // Pedido 2: Com Personalização (Mariana Silva)
    // Mixtape K7 (129.90) + 128GB (+69.00) = 198.90
    $stmtPed->execute([$cli1Id, $dispIds[2], $capIds[128], 'Em Produção', 198.90, date('Y-m-d H:i:s', strtotime('-2 days'))]);
    $ped2Id = $pdo->lastInsertId();

    $stmtPers = $pdo->prepare("INSERT INTO personalizacao (id_pedido, descricao, status, tipo_conteudo, texto_gravacao, cor_acabamento) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtPers->execute([
        $ped2Id,
        'Gravar na etiqueta frontal o nome "Trilha Sonora Mariana & Pedro - 2024". Pré-carregar pasta com vídeos e fotos da viagem à praia.',
        'Em Produção',
        'Fotos e Vídeos Pessoais',
        'Mariana & Pedro 2024',
        'Fita Vintage Preta e Dourada'
    ]);

    // Pedido 3: Com Personalização (Lucas Oliveira)
    // Pendrive EcoWood (99.90) + 256GB (+139.00) = 238.90
    $stmtPed->execute([$cli2Id, $dispIds[3], $capIds[256], 'Pendente', 238.90, date('Y-m-d H:i:s', strtotime('-3 hours'))]);
    $ped3Id = $pdo->lastInsertId();

    $stmtPers->execute([
        $ped3Id,
        'Gravação a laser com logotipo da empresa e fotos em alta definição do portfólio de arquitetura.',
        'Em Análise',
        'Portfólio de Fotos/Vídeos',
        'Studio Oliveira Arquitetura',
        'Madeira Nogueira Escura'
    ]);

    echo "[OK] Pedidos e Personalizações de demonstração criados.\n";
    echo "====================================================\n";
    echo "Instalação concluída com sucesso!\n";
    echo "====================================================\n";

} catch (Exception $e) {
    echo "\n[ERRO CRÍTICO]: " . $e->getMessage() . "\n";
    exit(1);
}
