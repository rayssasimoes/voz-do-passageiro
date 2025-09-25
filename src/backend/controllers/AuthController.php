<?php

namespace Backend\Controllers;

use Backend\Models\Usuario;
use PDO;
use PDOException;

class AuthController
{
    private $pdo;
    private $erros_login = [];
    private $erros_cadastro = [];
    private $mensagem_sucesso = '';
    private $active_tab = 'signin';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        if (isset($_SESSION['mensagem_sucesso'])) {
            $this->mensagem_sucesso = $_SESSION['mensagem_sucesso'];
            unset($_SESSION['mensagem_sucesso']);
            $this->active_tab = 'signin'; 
        }
    }

    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'signin') {
                $this->handleLogin();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'join') {
                $this->handleCadastro();
            }
        }
        
        if (!empty($this->erros_cadastro)) {
            $this->active_tab = 'join';
        }
        if (!empty($this->erros_login)) {
            $this->active_tab = 'signin';
        }

        global $erros_login, $erros_cadastro, $mensagem_sucesso, $active_tab;
        $erros_login = $this->erros_login;
        $erros_cadastro = $this->erros_cadastro;
        $mensagem_sucesso = $this->mensagem_sucesso;
        $active_tab = $this->active_tab;

        $base_path = dirname(__DIR__, 3);
        // REMOVEMOS A INCLUSÃO DO HEADER AQUI
        // require_once $base_path . '/src/views/templates/header.php'; 
        require_once $base_path . '/src/views/pages/auth_view.php';
        // require_once $base_path . '/src/views/templates/footer.php';
    }

    private function handleLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $this->erros_login[] = "Por favor, preencha todos os campos.";
        } else {
            try {
                $usuario = Usuario::buscarPorEmail($this->pdo, $email);

                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    $_SESSION['usuario_id'] = $usuario['id_usuario'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    header("Location: index.php");
                    exit();
                } else {
                    $this->erros_login[] = "Email ou senha inválidos.";
                }
            } catch (PDOException $e) {
                $this->erros_login[] = "Erro de sistema ao tentar login. Tente mais tarde.";
            }
        }
    }

    private function handleCadastro(): void
    {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';
        $aceito_termos = isset($_POST['aceito_termos']);

        if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
            $this->erros_cadastro[] = "Todos os campos são obrigatórios.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->erros_cadastro[] = "Formato de e-mail inválido.";
        } elseif (strlen($senha) < 12) {
            $this->erros_cadastro[] = "A senha deve ter pelo menos 12 caracteres.";
        } elseif ($senha !== $confirmar_senha) {
            $this->erros_cadastro[] = "As senhas não coincidem.";
        } elseif (!$aceito_termos) {
            $this->erros_cadastro[] = "Você deve aceitar os termos e condições.";
        } else {
            try {
                if (Usuario::buscarPorEmail($this->pdo, $email)) {
                    $this->erros_cadastro[] = "Este e-mail já está cadastrado.";
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    if (Usuario::criar($this->pdo, $nome, $email, $senha_hash)) {
                        $_SESSION['mensagem_sucesso'] = "Cadastro realizado com sucesso! Faça login.";
                        header('Location: login.php');
                        exit();
                    } else {
                        $this->erros_cadastro[] = "Erro ao cadastrar usuário. Tente novamente.";
                    }
                }
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    $this->erros_cadastro[] = "Este e-mail já está cadastrado.";
                } else {
                    $this->erros_cadastro[] = "Erro inesperado ao cadastrar. Tente novamente mais tarde.";
                }
            }
        }
    }
}