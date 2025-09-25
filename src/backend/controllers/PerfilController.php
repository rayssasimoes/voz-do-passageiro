<?php

class PerfilController
{
    public function getUsuarioNome()
    {
        if (isset($_SESSION['usuario_id'])) {
            // Simula a busca do nome do usuário no banco de dados
            return 'Usuário Logado';
        }
        return null;
    }

    public function getUsuarioEmail()
    {
        if (isset($_SESSION['usuario_email'])) {
            return $_SESSION['usuario_email'];
        }
        return null;
    }
}
