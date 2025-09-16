<?php
// 1. INICIALIZAÇÃO E CONFIGURAÇÃO
// =============================================================================

// Inicia a sessão
session_start();

// Protege a página: se não houver sessão ativa, redireciona para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Constantes de Conexão com o Banco de Dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'voz-do-passageiro');
define('DB_CHARSET', 'utf8mb4'); // Usar utf8mb4 para suporte completo a Unicode

// Inicialização de variáveis de mensagem
$mensagem_sucesso = '';
$mensagem_erro = '';

// 2. CONEXÃO COM O BANCO DE DADOS (PDO)
// =============================================================================

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Em um ambiente de produção, seria ideal logar o erro em vez de exibi-lo.
    // die() encerra a execução e exibe uma mensagem genérica para o usuário.
    die("Erro de sistema. Por favor, tente novamente mais tarde.");
}

// 3. PROCESSAMENTO DO FORMULÁRIO (SE HOUVER POST)
// =============================================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    switch ($action) {
        // --- Caso 1: Atualizar Perfil (Nome e Email) ---
        case 'update_profile':
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

            if (empty($nome) || empty($email)) {
                $mensagem_erro = "Nome e email são obrigatórios e o email deve ser válido.";
            } else {
                try {
                    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
                    $stmt->execute([$nome, $email, $_SESSION['usuario_id']]);

                    // Atualiza os dados na sessão para refletir a mudança imediatamente
                    $_SESSION['usuario_nome'] = $nome;
                    $mensagem_sucesso = "Perfil atualizado com sucesso!";
                } catch (PDOException $e) {
                    // Trata erro de email duplicado (ou outra constraint)
                    if ($e->getCode() == 23000) {
                        $mensagem_erro = "O email informado já está em uso por outra conta.";
                    } else {
                        $mensagem_erro = "Ocorreu um erro ao atualizar o perfil.";
                    }
                }
            }
            break;

        // --- Caso 2: Atualizar Senha ---
        case 'update_password':
            // Para senhas, é melhor acessar $_POST diretamente pois filter_input pode ter problemas com certos caracteres especiais.
            $senha_atual = $_POST['senha_atual'] ?? '';
            $nova_senha = $_POST['nova_senha'] ?? '';
            $confirma_nova_senha = $_POST['confirma_nova_senha'] ?? '';

            if (empty($senha_atual) || empty($nova_senha) || empty($confirma_nova_senha)) {
                $mensagem_erro = "Todos os campos de senha são obrigatórios.";
            } elseif ($nova_senha !== $confirma_nova_senha) {
                $mensagem_erro = "As novas senhas não coincidem.";
            } else {
                // Busca apenas o hash da senha atual para verificação
                $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
                $stmt->execute([$_SESSION['usuario_id']]);
                $user = $stmt->fetch();

                if ($user && password_verify($senha_atual, $user['senha'])) {
                    $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                    $stmt->execute([$novo_hash, $_SESSION['usuario_id']]);
                    $mensagem_sucesso = "Senha alterada com sucesso!";
                } else {
                    $mensagem_erro = "A senha atual está incorreta.";
                }
            }
            break;
    }
}

// 4. BUSCA DE DADOS PARA EXIBIÇÃO
// =============================================================================

// Busca os dados atuais do usuário para preencher o formulário
// (Faz isso *depois* do processamento do POST para exibir os dados já atualizados)
$stmt = $pdo->prepare("SELECT nome, email FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

// Se o usuário não for encontrado no banco por algum motivo, encerra a sessão e redireciona.
if (!$usuario) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// 5. RENDERIZAÇÃO DO HTML
// =============================================================================
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

                <?php if (!empty($mensagem_sucesso)): ?>
                    <div class="success-message"><?= htmlspecialchars($mensagem_sucesso) ?></div>
                <?php endif; ?>
                <?php if (!empty($mensagem_erro)): ?>
                    <div class="error-message"><?= htmlspecialchars($mensagem_erro) ?></div>
                <?php endif; ?>

                <form action="perfil.php" method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
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
