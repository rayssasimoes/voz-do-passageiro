<?php
// Arquivo: public/index.php
session_start();

// 1. CARREGAR AS FERRAMENTAS (Models e Conexão)
require_once '../src/core/Database.php';
require_once '../src/backend/models/Reclamacao.php';

// Obtém a conexão com o banco de dados
$pdo = Database::getInstance()->getConnection();

// --- LÓGICA DA API ---
// Este bloco lida com requisições do JavaScript. No futuro, ele terá seu próprio arquivo.
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'get_reclamacoes') {
        header('Content-Type: application/json');
        $stmt = $pdo->query("SELECT linha_onibus, tipo_reclamacao, descricao, autor, DATE_FORMAT(data_hora, '%d/%m/%Y às %H:%i') as data_formatada FROM reclamacoes ORDER BY data_hora DESC LIMIT 10");
        echo json_encode($stmt->fetchAll());
        exit;
    }
    if ($_GET['action'] == 'post_reclamacao' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['usuario_id'])) { http_response_code(403); echo json_encode(['sucesso' => false, 'mensagem' => 'Você precisa estar logado para enviar uma reclamação.']); exit; }
        header('Content-Type: application/json');
        $dados = json_decode(file_get_contents('php://input'), true);
        $linha = $dados['linha_onibus'] ?? ''; $tipo = $dados['tipo_reclamacao'] ?? ''; $descricao = $dados['descricao'] ?? '';
        $mostrar_nome = $dados['mostrar_nome'] ?? false;
        $autor_reclamacao = 'Anônimo';
        if ($mostrar_nome && isset($_SESSION['usuario_nome'])) { $autor_reclamacao = $_SESSION['usuario_nome']; }
        if (empty($linha) || empty($tipo) || empty($descricao)) { http_response_code(400); echo json_encode(['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios.']); exit; }
        $sql = "INSERT INTO reclamacoes (linha_onibus, tipo_reclamacao, descricao, autor) VALUES (:linha, :tipo, :descricao, :autor)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':linha' => $linha, ':tipo' => $tipo, ':descricao' => $descricao, ':autor' => $autor_reclamacao]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Reclamação registrada com sucesso!']);
        exit;
    }
}

// 2. EXECUTAR A LÓGICA DA PÁGINA (Buscar os dados para o HTML)
$piores_linhas = Reclamacao::buscarPioresLinhas($pdo);

$piores_linhas_nomes = [];
foreach ($piores_linhas as $linha) {
    $piores_linhas_nomes[] = $linha['linha_onibus'];
}

$melhores_linhas = Reclamacao::buscarMelhoresLinhas($pdo, $piores_linhas_nomes);
$reclamacoes_recentes = Reclamacao::buscarRecentes($pdo);

// 3. RENDERIZAR A PÁGINA (Chamar a View que contém o HTML)
require_once '../src/views/pages/home.php';