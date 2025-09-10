<?php
// Inicia a sessão para ter acesso às variáveis de sessão
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = array();

// Destrói a sessão no servidor
session_destroy();

// Redireciona o usuário para a página inicial
header("Location: index.php");
exit();
?>