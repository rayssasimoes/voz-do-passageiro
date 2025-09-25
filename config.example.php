<?php

// =================================================================
// CONFIGURAÇÃO OBRIGATÓRIA
// =================================================================

// Altere a linha abaixo para a URL raiz do seu projeto.
// Exemplo 1 (rodando na raiz do servidor, ex: http://localhost/):
// define('BASE_URL', 'http://localhost/public');

// Exemplo 2 (rodando em uma subpasta, ex: http://localhost/voz-do-passageiro/):
define('BASE_URL', 'http://localhost/voz-do-passageiro-main/public');


// =================================================================
// CONFIGURAÇÃO DO BANCO DE DADOS
// =================================================================

// Insira aqui as suas credenciais do banco de dados local.
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_voz-do-passageiro');
define('DB_USER', 'root');
define('DB_PASS', ''); // Deixe em branco se não houver senha

// Inicia a sessão para todo o site
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
