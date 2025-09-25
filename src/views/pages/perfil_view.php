<?php
$page_title = 'Meu Perfil - Voz do Passageiro';
$page_css = 'perfil_style.css';
require_once __DIR__ . '/../templates/header.php';
?>

<main class="container">
    <a href="<?php echo BASE_URL; ?>/index.php" class="btn-voltar" title="Voltar">
        <i class="arrow-left"></i>
    </a>
    <div class="perfil-header">
        <div class="perfil-img-placeholder"></div>
        <h1><?php echo $usuario_nome; ?></h1>
        <p><?php echo $usuario_email; ?></p>
    </div>

    <div class="perfil-content">
        <section class="info-pessoal">
            <h2>Informações Pessoais</h2>
            <form id="form-info-pessoal">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $usuario_nome; ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $usuario_email; ?>" disabled>
                </div>
                <button type="button" id="btn-editar-info" class="btn btn-primary">Editar</button>
                <button type="submit" id="btn-salvar-info" class="btn btn-primary hidden">Salvar</button>
            </form>
        </section>

        <section class="gerenciar-conta">
            <h2>Gerenciar Conta</h2>
            <button id="btn-alterar-senha" class="btn btn-secondary">Alterar Senha</button>
            <button id="btn-apagar-conta" class="btn btn-danger">Apagar Conta</button>
        </section>
    </div>

    <div id="modal-alterar-senha" class="modal-overlay">
        <div class="modal-box">
            <h2>Alterar Senha</h2>
            <form id="form-alterar-senha">
                <div class="form-group">
                    <label for="senha-atual">Senha Atual</label>
                    <input type="password" id="senha-atual" required>
                </div>
                <div class="form-group">
                    <label for="nova-senha">Nova Senha</label>
                    <input type="password" id="nova-senha" required>
                </div>
                <div class="form-group">
                    <label for="confirmar-nova-senha">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar-nova-senha" required>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" id="btn-cancelar-alterar-senha" class="btn btn-secondary">Cancelar</button>
            </form>
        </div>
    </div>

    <div id="modal-apagar-conta" class="modal-overlay">
        <div class="modal-box">
            <p>Tem certeza que deseja apagar sua conta? Esta ação é irreversível.</p>
            <div class="modal-actions">
                <button id="btn-confirmar-apagar-conta" class="btn btn-danger">Apagar</button>
                <button id="btn-cancelar-apagar-conta" class="btn btn-secondary">Cancelar</button>
            </div>
        </div>
    </div>
</main>

<?php
$page_js = 'perfil_script.js';
require_once __DIR__ . '/../templates/footer.php';
?>