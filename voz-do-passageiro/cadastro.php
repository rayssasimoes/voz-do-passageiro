<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
$erro_cadastro = '';
$sucesso_cadastro = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'voz-do-passageiro');
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) { $erro_cadastro = "Erro de sistema. Tente mais tarde."; }

    if(empty($erro_cadastro)) {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirma_senha = $_POST['confirma_senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha)) {
            $erro_cadastro = "Todos os campos são obrigatórios.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro_cadastro = "Formato de email inválido.";
        } elseif (strlen($senha) < 6) {
            $erro_cadastro = "A senha deve ter pelo menos 6 caracteres.";
        } elseif ($senha !== $confirma_senha) {
            $erro_cadastro = "As senhas não coincidem.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $erro_cadastro = "Este email já está cadastrado.";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
                if ($stmt->execute([$nome, $email, $senha_hash])) {
                    // Auto-login após cadastro
                    $_SESSION['usuario_id'] = $pdo->lastInsertId();
                    $_SESSION['usuario_nome'] = $nome;
                    header("Location: index.php?cadastro=sucesso");
                    exit();
                } else {
                    $erro_cadastro = "Ocorreu um erro ao criar a conta. Tente novamente.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Voz do Passageiro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth_style.css">
</head>
<body>
    <div class="auth-wrapper">
        <aside class="auth-sidebar">
            <h1>Junte-se a Nós</h1>
            <p>Crie sua conta e comece a registrar suas experiências. Sua participação é fundamental para um transporte público de qualidade.</p>
        </aside>
        <main class="auth-main">
            <div class="auth-container">
                <h2>Crie sua Conta</h2>
                <?php if (!empty($erro_cadastro)): ?>
                    <div class="error-message"><?= htmlspecialchars($erro_cadastro) ?></div>
                <?php endif; ?>
                <form action="cadastro.php" method="POST">
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha (mínimo 6 caracteres)</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    <div class="form-group">
                        <label for="confirma_senha">Confirme sua Senha</label>
                        <input type="password" id="confirma_nova_senha" name="confirma_senha" required>
                    </div>
                    <button type="submit" class="auth-button">Cadastrar</button>
                </form>
                <p class="auth-link">
                    Já tem uma conta? <a href="login.php">Faça login</a>
                </p>
                <p class="auth-link">
                    <a href="index.php">Voltar para a página inicial</a>
                </p>
            </div>
        </main>
    </div>
</body>
</html>
