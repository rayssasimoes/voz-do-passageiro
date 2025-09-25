        </div>
    </main>
    <footer class="main-footer">
        <div class="container footer-content">
            <nav class="footer-nav">
                <ul class="footer-links-list">
                    <li><a href="#" class="footer-link">Política de Privacidade</a></li>
                    <li><a href="#" class="footer-link">Termos de Uso</a></li>
                    <li><a href="#" class="footer-link">Contato</a></li>
                </ul>
            </nav>
            <div class="footer-divider"></div>
            <div class="footer-bottom-text">
                <p>&copy; <?php echo date('Y'); ?> Voz do Passageiro</p>
                <p>Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <div id="logout-confirm-overlay" class="modal-overlay">
        <div class="modal-box">
            <p>Realmente deseja sair?</p>
            <div class="modal-actions">
                <button id="confirm-logout-btn" class="btn btn-primary">Confirmar</button>
                <button id="cancel-logout-btn" class="btn btn-secondary">Cancelar</button>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>/assets/js/script.js"></script>
    <?php if (isset($page_js)):
        $script_path = BASE_URL . '/assets/js/' . $page_js;
        echo "<script src=\"$script_path\"></script>";
    endif; ?>
</body>
</html>