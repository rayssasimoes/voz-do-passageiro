<?php
session_start();
// Protege a página: se não estiver logado, redireciona para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Lógica de conexão e atualização
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'voz-do-passageiro');
$mensagem_sucesso = '';
$mensagem_erro = '';
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) { die("Erro de sistema."); }

// Busca os dados atuais do usuário para preencher o formulário
$stmt = $pdo->prepare("SELECT nome, email, senha FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

// Processa o formulário de atualização de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Atualização de Nome e Email
    if (isset($_POST['action']) && $_POST['action'] == 'update_profile') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        if (empty($nome) || empty($email)) {
            $mensagem_erro = "Nome e email não podem estar vazios.";
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
            $stmt->execute([$nome, $email, $_SESSION['usuario_id']]);
            $_SESSION['usuario_nome'] = $nome; // Atualiza o nome na sessão
            $mensagem_sucesso = "Perfil atualizado com sucesso!";
            $usuario['nome'] = $nome; // Atualiza para o formulário
            $usuario['email'] = $email;
        }
    }

    // Atualização de Senha
    if (isset($_POST['action']) && $_POST['action'] == 'update_password') {
        $senha_atual = $_POST['senha_atual'] ?? '';
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirma_nova_senha = $_POST['confirma_nova_senha'] ?? '';

        if(empty($senha_atual) || empty($nova_senha) || empty($confirma_nova_senha)) {
            $mensagem_erro = "Todos os campos de senha são obrigatórios.";
        } elseif ($nova_senha !== $confirma_nova_senha) {
            $mensagem_erro = "As novas senhas não coincidem.";
        } else {
            if (password_verify($senha_atual, $usuario['senha'])) {
                $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                $stmt->execute([$novo_hash, $_SESSION['usuario_id']]);
                $mensagem_sucesso = "Senha alterada com sucesso!";
            } else {
                $mensagem_erro = "A senha atual está incorreta.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Voz do Passageiro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth_style.css">
</head>
<body>
    <div class="auth-wrapper">
        <aside class="auth-sidebar">
            <h1>Meu Perfil</h1>
            <p>Mantenha seus dados atualizados. Lembre-se que seu nome só será exibido em reclamações se você permitir.</p>
        </aside>
        <main class="auth-main">
            <div class="auth-container">
                <h2>Editar Informações</h2>
                <?php if (!empty($mensagem_sucesso)): ?><div class="success-message"><?= htmlspecialchars($mensagem_sucesso) ?></div><?php endif; ?>
                <?php if (!empty($mensagem_erro)): ?><div class="error-message"><?= htmlspecialchars($mensagem_erro) ?></div><?php endif; ?>

                <form action="perfil.php" method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>
                    <button type="submit" class="auth-button">Salvar Alterações</button>
                </form>

                <hr style="margin: 30px 0; border: none; border-top: 1px solid var(--cor-borda);">

                <h2>Alterar Senha</h2>
                <form action="perfil.php" method="POST">
                    <input type="hidden" name="action" value="update_password">
                    <div class="form-group">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" required>
                    </div>
                    <div class="form-group">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required>
                    </div>
                    <div class="form-group">
                        <label for="confirma_nova_senha">Confirme a Nova Senha</label>
                        <input type="password" id="confirma_nova_senha" name="confirma_nova_senha" required>
                    </div>
                    <button type="submit" class="auth-button">Alterar Senha</button>
                </form>
                 <p class="auth-link">
                    <a href="index.php">Voltar para a página inicial</a>
                </p>
            </div>
        </main>
    </div>
</body>
</html>
