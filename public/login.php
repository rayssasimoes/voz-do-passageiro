<?php
session_start();

// Redireciona se o usuário já estiver logado (para a página inicial logada)
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Carrega as dependências para o controlador de autenticação
require_once __DIR__ . '/../src/core/Database.php';
require_once __DIR__ . '/../src/backend/models/Usuario.php';
require_once __DIR__ . '/../src/backend/controllers/AuthController.php'; // Usa o novo AuthController

use Backend\Controllers\AuthController; // Usa o namespace correto

// Obtém a conexão com o banco de dados
$db = Database::getInstance();
$pdo = $db->getConnection();

// Cria uma instância do novo controlador de autenticação e o executa
$controller = new AuthController($pdo);
$controller->handleRequest();
?>