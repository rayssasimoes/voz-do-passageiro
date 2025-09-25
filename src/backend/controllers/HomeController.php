<?php

namespace Backend\Controllers;

use Backend\Models\Reclamacao;
use Backend\Models\Usuario;

class HomeController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Renderiza a página home.
     */
    public function index(): void
    {
        $usuarioLogado = isset($_SESSION['usuario_id']); 
        $usuario_nome = $_SESSION['usuario_nome'] ?? null;

        $reclamacoes_recentes = Reclamacao::buscarRecentes($this->pdo);
        $piores_linhas = Reclamacao::buscarPioresLinhas($this->pdo);
        
        $piores_linhas_nomes = array_map(function($linha) {
            return $linha['linha_onibus'];
        }, $piores_linhas);
        
        $melhores_linhas = Reclamacao::buscarMelhoresLinhas($this->pdo, $piores_linhas_nomes);

        $dados = [
            'usuarioLogado' => $usuarioLogado,
            'usuario_nome' => $usuario_nome,
            'melhores_linhas' => $melhores_linhas,
            'piores_linhas' => $piores_linhas,
            'reclamacoes_recentes' => $reclamacoes_recentes
        ];
        
        extract($dados); 

        // Carrega as views (header, home_view, footer)
        $base_path = dirname(__DIR__, 3); 

        // require $base_path . '/src/views/templates/header.php'; 
        require $base_path . '/src/views/pages/home_view.php'; // <<< CORRIGIDO AQUI: home_view.php
        // require $base_path . '/src/views/templates/footer.php';
    }
}