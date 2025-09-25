<?php

namespace Backend\Controllers;

use PDO; 
use PDOException; 

// Inclui os modelos necessários
// ATENÇÃO: Caminhos corrigidos para os modelos. De controllers/ para backend/models/
require_once __DIR__ . '/../models/Reclamacao.php';
require_once __DIR__ . '/../models/Usuario.php';

use Backend\Models\Reclamacao;
use Backend\Models\Usuario;

class ReclamacaoController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        // REMOVIDO: $this->reclamacaoModel = new Reclamacao($pdo); pois os métodos são estáticos.
        // REMOVIDO: $this->usuarioModel = new Usuario($pdo);
    }

    public function postReclamacao(int $usuario_id)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
            exit();
        }

        $linha_onibus = filter_input(INPUT_POST, 'linha_onibus', FILTER_SANITIZE_STRING);
        $tipo_reclamacao = filter_input(INPUT_POST, 'tipo_reclamacao', FILTER_SANITIZE_STRING);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
        // O checkbox envia "on" se marcado, ou não envia nada se desmarcado.
        // Se estiver "on", ou se houver um valor que FILTER_VALIDATE_BOOLEAN interprete como true, será true.
        // Caso contrário, será false.
        $mostrar_nome = filter_input(INPUT_POST, 'mostrar_nome', FILTER_VALIDATE_BOOLEAN); 

        if (empty($linha_onibus) || empty($tipo_reclamacao) || empty($descricao)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios.']);
            exit();
        }

        try {
            $reclamacaoId = Reclamacao::criar(
                $this->pdo,
                $usuario_id,
                $linha_onibus,
                $tipo_reclamacao,
                $descricao,
                $mostrar_nome
            );

            if ($reclamacaoId) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Reclamação registrada com sucesso!', 'id' => $reclamacaoId]);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao registrar reclamação.']);
            }
        } catch (PDOException $e) {
            error_log("Erro ao registrar reclamação: " . $e->getMessage());
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno do servidor ao registrar reclamação.']);
        }
        exit();
    }

    public function getMinhasReclamacoes(int $usuario_id)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
            exit();
        }

        try {
            $reclamacoes = Reclamacao::buscarPorUsuario($this->pdo, $usuario_id);
            echo json_encode(['sucesso' => true, 'reclamacoes' => $reclamacoes]);

        } catch (PDOException $e) {
            error_log("Erro ao obter minhas reclamações: " . $e->getMessage());
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno do servidor ao buscar reclamações.']);
        }
        exit();
    }

    public function deleteReclamacao(int $usuario_id)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
            exit();
        }

        $reclamacao_id = filter_input(INPUT_POST, 'reclamacao_id', FILTER_VALIDATE_INT);

        if (!$reclamacao_id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID da reclamação inválido.']);
            exit();
        }

        try {
            $sucesso = Reclamacao::excluir($this->pdo, $reclamacao_id, $usuario_id);

            if ($sucesso) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Reclamação excluída com sucesso.']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Não foi possível excluir a reclamação. Pode não existir ou você não é o autor.']);
            }
        } catch (PDOException $e) {
            error_log("Erro ao excluir reclamação: " . $e->getMessage());
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno do servidor ao excluir reclamação.']);
        }
        exit();
    }

    public function getPublicReclamacoes()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
            exit();
        }

        try {
            $reclamacoes = Reclamacao::buscarRecentes($this->pdo);
            echo json_encode(['sucesso' => true, 'reclamacoes' => $reclamacoes]);

        } catch (PDOException $e) {
            error_log("Erro ao obter reclamações públicas: " . $e->getMessage());
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno do servidor ao buscar reclamações.']);
        }
        exit();
    }
}