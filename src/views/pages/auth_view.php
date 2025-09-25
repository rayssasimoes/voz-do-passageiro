<?php
// Define a BASE_URL se não estiver definida (importante para execução isolada)
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../config.php';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voz do Passageiro - Autenticação</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/auth_style.css">
</head>
<body class="auth-body">
    <main>
        <div class="auth-page-container">
            <div class="auth-left-panel">
                <div class="branding">
                    <h1>Voz do Passageiro</h1>
                    <p>Sua voz para um transporte público melhor.</p>
                </div>
            </div>

            <div class="auth-right-panel">
                <div class="auth-card">
                    <div class="auth-tabs">
                        <button class="tab-button <?php echo ($active_tab === 'signin' || empty($active_tab)) ? 'active' : ''; ?>" data-tab="signin">Entrar</button>
                        <button class="tab-button <?php echo ($active_tab === 'join') ? 'active' : ''; ?>" data-tab="join">Junte-se</button>
                    </div>

                    <?php if (!empty($mensagem_sucesso)):
                        echo "<div class=\"alert alert-success\" role=\"alert\">" . htmlspecialchars($mensagem_sucesso) . "</div>";
                    endif; ?>

                    <div id="signin-form" class="auth-form-content <?php echo ($active_tab === 'signin' || empty($active_tab)) ? 'active' : ''; ?>">
                        <h2>Bem vindo (a) de volta!</h2>
                        <?php if (!empty($erros_login)):
                            echo '<div class="alert alert-danger" role="alert"><ul>';
                            foreach ($erros_login as $erro) {
                                echo "<li>" . htmlspecialchars($erro) . "</li>";
                            }
                            echo '</ul></div>';
                        endif; ?>
                        <form action="login.php" method="POST">
                            <input type="hidden" name="action" value="signin">
                            <div class="form-group">
                                <label for="signin-email">Email:</label>
                                <input type="email" id="signin-email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="signin-senha">Senha:</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="signin-senha" name="senha" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </form>
                    </div>

                    <div id="join-form" class="auth-form-content <?php echo ($active_tab === 'join') ? 'active' : ''; ?>">
                        <h2>Crie sua Conta</h2>
                        <?php if (!empty($erros_cadastro)):
                            echo '<div class="alert alert-danger" role="alert"><ul>';
                            foreach ($erros_cadastro as $erro) {
                                echo "<li>" . htmlspecialchars($erro) . "</li>";
                            }
                            echo '</ul></div>';
                        endif; ?>
                        <form action="login.php" method="POST">
                            <input type="hidden" name="action" value="join">
                            <div class="form-group">
                                <label for="join-nome">Nome Completo:</label>
                                <input type="text" id="join-nome" name="nome" required value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="join-email">Email:</label>
                                <input type="email" id="join-email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="join-senha">Senha (mín. 12 caracteres):</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="join-senha" name="senha" minlength="12" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="join-confirmar-senha">Confirme sua Senha:</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="join-confirmar-senha" name="confirmar_senha" required>
                                </div>
                            </div>
                            <div class="form-group-checkbox">
                                <input type="checkbox" id="aceito_termos" name="aceito_termos" required>
                                <label for="aceito_termos">Eu aceito os <a href="#">Termos e Condições</a></label>
                            </div>
                            <button type="submit" class="btn btn-primary">Criar Conta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="<?php echo BASE_URL; ?>/assets/js/auth_script.js"></script>
</body>
</html>
