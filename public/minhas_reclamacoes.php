<?php
session_start();

// Inclui o controlador para obter os dados necessários
require_once '../src/backend/controllers/MinhasReclamacoesController.php';

// Cria uma instância do controlador
$controller = new MinhasReclamacoesController();

// Obtém os dados do usuário, se estiver logado
$usuario_nome = $controller->getUsuarioNome();
$usuario_email = $controller->getUsuarioEmail();

// Inclui o conteúdo da página de minhas reclamações
require_once '../src/views/pages/minhas_reclamacoes_view.php';
?>