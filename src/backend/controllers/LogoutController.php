<?php

namespace Backend\Controllers;

class LogoutController {
    public function logout(): void {

        // Limpa todas as variáveis de sessão
        $_SESSION = [];

        // Remove o cookie de sessão.
        // Isso é importante para realmente destruir a sessão no lado do cliente.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finalmente, destrói a sessão.
        session_destroy();

        // Redireciona para a página de login.
        header("Location: login.php");
        exit();
    }
}