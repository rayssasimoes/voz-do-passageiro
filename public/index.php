<?php
// Inicia a sessão em todas as páginas para verificar o status de login do usuário.
session_start();

// Carrega as dependências essenciais
require_once __DIR__ . '/../src/core/Database.php';
require_once __DIR__ . '/../src/backend/models/Reclamacao.php'; 
require_once __DIR__ . '/../src/backend/models/Usuario.php';    
require_once __DIR__ . '/../src/backend/controllers/HomeController.php';

// Adiciona a declaração 'use' para a classe HomeController
use Backend\Controllers\HomeController;

// --- NOVA LÓGICA DE FLUXO PARA PÁGINA INICIAL ---
// Se o usuário NÃO ESTIVER logado, redireciona para a página de login.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
// ---------------------------------------------------


// Se o usuário ESTIVER logado, proceede para carregar a HomeController normalmente.
$db = Database::getInstance();
$pdo = $db->getConnection();

$controller = new HomeController($pdo);
$controller->index();