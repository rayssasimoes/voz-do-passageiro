<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../src/core/Database.php';
require_once __DIR__ . '/../src/backend/models/Reclamacao.php';

// Adiciona a declaração 'use' para que o PHP encontre a classe Reclamacao
use Backend\Models\Reclamacao;

/**
 * Função auxiliar para enviar respostas JSON e terminar o script.
 */
function send_json($data, $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

// Conexão com o banco de dados usando a classe Database (Singleton)
try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
} catch (PDOException $e) {
    // Não exponha detalhes do erro em produção
    send_json(['sucesso' => false, 'mensagem' => 'Erro de conexão com o banco de dados.'], 500);
}

$action = $_GET['action'] ?? '';

switch ($action) {
    // --- Ação para buscar as reclamações do usuário logado ---
    case 'get_reclamacoes_pessoais':
        if (!isset($_SESSION['usuario_id'])) {
            send_json(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.'], 403);
        }
        $reclamacoes = Reclamacao::buscarPorUsuario($pdo, $_SESSION['usuario_id']);
        send_json($reclamacoes);
        break;

    // --- Ação para adicionar uma nova reclamação ---
    case 'add_reclamacao':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            send_json(['sucesso' => false, 'mensagem' => 'Método não permitido.'], 405);
        }
        if (!isset($_SESSION['usuario_id'])) {
            send_json(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.'], 403);
        }

        $dados = json_decode(file_get_contents('php://input'), true);
        if (!$dados) {
            send_json(['sucesso' => false, 'mensagem' => 'Dados inválidos.'], 400);
        }

        // Adiciona dados do usuário logado para o model
        $dados['usuario_id'] = $_SESSION['usuario_id'];
        $dados['usuario_nome'] = $_SESSION['usuario_nome'] ?? 'Anônimo';

        if (Reclamacao::criar($pdo, $dados)) {
            send_json(['sucesso' => true, 'mensagem' => 'Reclamação registrada com sucesso!']);
        } else {
            send_json(['sucesso' => false, 'mensagem' => 'Erro ao registrar reclamação.'], 500);
        }
        break;

    // --- Ação para deletar uma reclamação ---
    case 'delete_reclamacao':
        if (!isset($_SESSION['usuario_id'])) {
            send_json(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.'], 403);
        }

        $dados = json_decode(file_get_contents('php://input'), true);
        $reclamacao_id = $dados['reclamacao_id'] ?? null;

        if (!$reclamacao_id) {
            send_json(['sucesso' => false, 'mensagem' => 'ID da reclamação não fornecido.'], 400);
        }

        // Tenta excluir usando o ID do usuário da sessão para segurança
        if (Reclamacao::excluir($pdo, $reclamacao_id, $_SESSION['usuario_id'])) {
            send_json(['sucesso' => true, 'mensagem' => 'Reclamação excluída com sucesso!']);
        } else {
            send_json(['sucesso' => false, 'mensagem' => 'Erro ao excluir a reclamação. Verifique se você é o autor.'], 500);
        }
        break;

    default:
        send_json(['sucesso' => false, 'mensagem' => 'Endpoint da API não encontrado.'], 404);
        break;
}
