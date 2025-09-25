<?php
session_start();

// Inclui o controlador para obter os dados necessários
require_once '../src/backend/controllers/PerfilController.php';

// Cria uma instância do controlador
$controller = new PerfilController();

// Obtém os dados do usuário, se estiver logado
$usuario_nome = $controller->getUsuarioNome();
$usuario_email = $controller->getUsuarioEmail();

// Inclui o conteúdo da página de meu perfil
require_once '../src/views/pages/perfil_view.php';
?>