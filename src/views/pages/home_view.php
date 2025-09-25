<?php
$page_title = 'Voz do Passageiro';
$page_css = 'home_style.css';
require_once __DIR__ . '/../templates/header.php';
?>

<main class="main-content-area container">
    <section class="info-box-home">
        <div class="info-box-content">
            <h2 id="usuario-nome">Olá, <?php echo htmlspecialchars($usuario_nome ?? 'Usuário'); ?>!</h2>
            <p>Acompanhe as reclamações e rankings do transporte público.</p>
        </div>
    </section>
    <section class="lists-container">
        <div class="reclamacoes-section">
            <h3>Reclamações Recentes</h3>
            <?php if (!empty($reclamacoes_recentes)):
                echo '<div class="reclamacoes-grid">';
                foreach ($reclamacoes_recentes as $rec) {
                    echo '<div class="reclamacao-card">';
                    echo "<h4>Linha: " . htmlspecialchars($rec['linha_onibus']) . "</h4>";
                    echo "<p class=\"tipo-reclamacao\">" . htmlspecialchars($rec['tipo_reclamacao']) . "</p>";
                    echo "<p class=\"descricao\">" . htmlspecialchars($rec['descricao']) . "</p>";
                    echo '<div class="reclamacao-meta">';
                    echo "<span class=\"autor\">" . htmlspecialchars($rec['autor'] ?? 'Anônimo') . "</span>";
                    echo "<span class=\"data\">" . htmlspecialchars($rec['data_formatada']) . "</span>";
                    echo '</div></div>';
                }
                echo '</div>';
            else:
                echo '<p class="sem-dados">Nenhuma reclamação recente encontrada.</p>';
            endif; ?>
        </div>

        <div class="rankings-section">
            <div class="ranking-card">
                <h3>🏆 Piores Linhas</h3>
                <?php if (!empty($piores_linhas)):
                    echo '<ol class="ranking-list">';
                    foreach ($piores_linhas as $linha) {
                        echo "<li><span class=\"linha-nome\">" . htmlspecialchars($linha['linha_onibus']) . "</span><span class=\"linha-count\">" . htmlspecialchars($linha['total_reclamacoes']) . " reclamações</span></li>";
                    }
                    echo '</ol>';
                else:
                    echo '<p class="sem-dados">Nenhuma linha com reclamações para exibir.</p>';
                endif; ?>
            </div>

            <div class="ranking-card">
                <h3>⭐ Melhores Linhas</h3>
                <?php if (!empty($melhores_linhas)):
                    echo '<ol class="ranking-list">';
                    foreach ($melhores_linhas as $linha) {
                        echo "<li><span class=\"linha-nome\">" . htmlspecialchars($linha['linha_onibus']) . "</span><span class=\"linha-count\">" . htmlspecialchars($linha['total_reclamacoes']) . " reclamações</span></li>";
                    }
                    echo '</ol>';
                else:
                    echo '<p class="sem-dados">Nenhuma linha com poucas reclamações para exibir.</p>';
                endif; ?>
            </div>
        </div>
    </section>
</main>

<?php
$page_js = 'home_script.js';
require_once __DIR__ . '/../templates/footer.php';
?>