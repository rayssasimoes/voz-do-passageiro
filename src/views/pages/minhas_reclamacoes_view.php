<?php
$page_title = 'Minhas Reclamações - Voz do Passageiro';
$page_css = 'minhas_reclamacoes_style.css';
require_once __DIR__ . '/../templates/header.php';
?>

<main class="container">
    <a href="<?php echo BASE_URL; ?>/index.php" class="btn-voltar" title="Voltar">
        <i class="arrow-left"></i>
    </a>
    <div class="nova-reclamacao-container">
        <button id="btn-nova-reclamacao" class="btn-primary">Fazer uma reclamação</button>
    </div>
    <section id="form-reclamacao-container" class="form-container hidden">
        <h2>Nova Reclamação</h2>
        <form id="form-reclamacao">
            <div class="form-group">
                <label for="linha_onibus">Linha do Ônibus:</label>
                <input type="text" id="linha_onibus" name="linha_onibus" required minlength="2">
            </div>
            <div class="form-group">
                <label for="tipo_reclamacao">Tipo de Reclamação:</label>
                <select id="tipo_reclamacao" name="tipo_reclamacao" required>
                    <option value="">Selecione...</option>
                    <option value="Atraso">Atraso</option>
                    <option value="Superlotação">Superlotação</option>
                    <option value="Má conservação">Má conservação do veículo</option>
                    <option value="Conduta do motorista">Conduta do motorista/cobrador</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="descricao">Descreva o ocorrido:</label>
                <textarea id="descricao" name="descricao" rows="4" required minlength="6"></textarea>
            </div>
            <div class="form-group-checkbox">
                <input type="checkbox" id="mostrar_nome" name="mostrar_nome">
                <label for="mostrar_nome">Mostrar meu nome publicamente</label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Enviar Reclamação</button>
            </div>
        </form>
        <div id="form-status"></div>
    </section>

    <div class="reclamacoes-header">
        <h1>Minhas Reclamações</h1>
    </div>

    <section id="historico-reclamacoes">
        <h2>Histórico de Reclamações</h2>
        <div id="reclamacoes-list"></div>
    </section>

    <div id="delete-confirm-overlay" class="modal-overlay">
        <div class="modal-box">
            <p>Realmente deseja apagar esta reclamação?</p>
            <div class="modal-actions">
                <button id="confirm-delete-btn" class="btn btn-primary">Confirmar</button>
                <button id="cancel-delete-btn" class="btn btn-secondary">Cancelar</button>
            </div>
        </div>
    </div>
    <script>
        const usuario_email = "<?php echo $usuario_email; ?>";
    </script>
</main>

<?php
$page_js = 'minhas_reclamacoes_script.js';
require_once __DIR__ . '/../templates/footer.php';
?>