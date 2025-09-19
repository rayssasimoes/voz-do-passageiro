<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
$erro_login = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'voz-do-passageiro');
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) { $erro_login = "Erro de sistema. Tente mais tarde."; }
    
    if (empty($erro_login)) {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        if (empty($email) || empty($senha)) {
            $erro_login = "Por favor, preencha todos os campos.";
        } else {
            $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                header("Location: index.php");
                exit();
            } else {
                $erro_login = "Email ou senha inválidos.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Voz do Passageiro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth_style.css">
</head>
<body>
    <div class="auth-wrapper">
        <aside class="auth-sidebar">
            <h1>Bem-vindo de volta!</h1>
            <p>Sua voz transforma o transporte. Faça login para continuar fazendo a diferença.</p>
        </aside>
        <main class="auth-main">
            <div class="auth-container">
                <h2>Acesse sua Conta</h2>
                <?php if (!empty($erro_login)): ?>
                    <div class="error-message"><?= htmlspecialchars($erro_login) ?></div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="auth-button">Entrar</button>
                </form>
                <p class="auth-link">
                    Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
                </p>
                 <p class="auth-link">
                    <a href="index.php">Voltar para a página inicial</a>
                </p>
            </div>
        </main>
    </div>
</body>
</html>
