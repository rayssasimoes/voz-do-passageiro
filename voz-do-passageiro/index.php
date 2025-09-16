<?php
session_start();

// --- LÓGICA PHP ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'voz-do-passageiro');
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false, ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) { die("Erro fatal de conexão com o banco de dados: " . $e->getMessage()); }

// --- LÓGICA DA API ---
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'get_reclamacoes') {
        header('Content-Type: application/json');
        $stmt = $pdo->query("SELECT linha_onibus, tipo_reclamacao, descricao, autor, DATE_FORMAT(data_hora, '%d/%m/%Y às %H:%i') as data_formatada FROM reclamacoes ORDER BY data_hora DESC LIMIT 10");
        echo json_encode($stmt->fetchAll());
        exit;
    }
    if ($_GET['action'] == 'post_reclamacao' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['usuario_id'])) { http_response_code(403); echo json_encode(['sucesso' => false, 'mensagem' => 'Você precisa estar logado para enviar uma reclamação.']); exit; }
        header('Content-Type: application/json');
        $dados = json_decode(file_get_contents('php://input'), true);
        $linha = $dados['linha_onibus'] ?? ''; $tipo = $dados['tipo_reclamacao'] ?? ''; $descricao = $dados['descricao'] ?? '';
        $mostrar_nome = $dados['mostrar_nome'] ?? false;
        $autor_reclamacao = 'Anônimo';
        if ($mostrar_nome && isset($_SESSION['usuario_nome'])) { $autor_reclamacao = $_SESSION['usuario_nome']; }
        if (empty($linha) || empty($tipo) || empty($descricao)) { http_response_code(400); echo json_encode(['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios.']); exit; }
        $sql = "INSERT INTO reclamacoes (linha_onibus, tipo_reclamacao, descricao, autor) VALUES (:linha, :tipo, :descricao, :autor)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':linha' => $linha, ':tipo' => $tipo, ':descricao' => $descricao, ':autor' => $autor_reclamacao]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Reclamação registrada com sucesso!']);
        exit;
    }
}

// --- LÓGICA PARA BUSCAR DADOS PARA A PÁGINA ---
$stmt_recentes = $pdo->query("SELECT linha_onibus, tipo_reclamacao, descricao, autor, DATE_FORMAT(data_hora, '%d/%m/%Y às %H:%i') as data_formatada FROM reclamacoes ORDER BY data_hora DESC LIMIT 5");
$reclamacoes_recentes = $stmt_recentes->fetchAll();

$stmt_piores = $pdo->query("SELECT linha_onibus, COUNT(*) as total_reclamacoes FROM reclamacoes GROUP BY linha_onibus ORDER BY total_reclamacoes DESC LIMIT 5");
$piores_linhas = $stmt_piores->fetchAll();

$piores_linhas_nomes = [];
foreach ($piores_linhas as $linha) {
    $piores_linhas_nomes[] = $linha['linha_onibus'];
}

if(!empty($piores_linhas_nomes)) {
    $placeholders = implode(',', array_fill(0, count($piores_linhas_nomes), '?'));
    $stmt_melhores = $pdo->prepare("SELECT linha_onibus, COUNT(*) as total_reclamacoes FROM reclamacoes WHERE linha_onibus NOT IN ($placeholders) GROUP BY linha_onibus HAVING total_reclamacoes > 0 ORDER BY total_reclamacoes ASC LIMIT 5");
    $stmt_melhores->execute($piores_linhas_nomes);
} else {
    $stmt_melhores = $pdo->query("SELECT linha_onibus, COUNT(*) as total_reclamacoes FROM reclamacoes GROUP BY linha_onibus HAVING total_reclamacoes > 0 ORDER BY total_reclamacoes ASC LIMIT 5");
}
$melhores_linhas = $stmt_melhores->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voz do Passageiro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav-menu">
        <div class="nav-container">
            <div class="nav-left">
                <button id="theme-toggle" class="theme-button">Mudar Tema</button>
            </div>
            <div class="nav-center">
                <a href="index.php" class="nav-title">Voz do Passageiro</a>
            </div>
            <div class="nav-right">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="perfil.php" class="nav-button">Meu Perfil</a>
                    <a href="logout.php" class="nav-button">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-button">Login</a>
                    <a href="cadastro.php" class="nav-button nav-button-cadastro">Cadastre-se</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main>
        <section class="info-box-home">
            <div class="info-box-content">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <h2>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>
                    <p>Registre sua reclamação e ajude a melhorar o transporte público.</p>
                <?php else: ?>
                    <h2>Bem-vindo(a) ao Voz do Passageiro</h2>
                    <p>Descubra a avaliação dos passageiros. Veja os rankings das linhas e as últimas reclamações para entender a situação real do transporte público.</p>
                <?php endif; ?>
            </div>
        </section>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <section class="form-container">
                <h2>Nova Reclamação</h2>
                <form id="form-reclamacao">
                    <div class="form-group"><label for="linha_onibus">Linha do Ônibus:</label><input type="text" id="linha_onibus" name="linha_onibus" placeholder="Ex: 874T-10" required></div>
                    <div class="form-group"><label for="tipo_reclamacao">Tipo de Reclamação:</label><select id="tipo_reclamacao" name="tipo_reclamacao" required><option value="">Selecione...</option><option value="Atraso">Atraso</option><option value="Superlotação">Superlotação</option><option value="Má conservação">Má conservação do veículo</option><option value="Conduta do motorista">Conduta do motorista/cobrador</option><option value="Outro">Outro</option></select></div>
                    <div class="form-group"><label for="descricao">Descreva o ocorrido:</label><textarea id="descricao" name="descricao" rows="4" required></textarea></div>
                    <div class="form-group-checkbox"><input type="checkbox" id="mostrar_nome" name="mostrar_nome"><label for="mostrar_nome">Mostrar meu nome publicamente na reclamação</label></div>
                    <button type="submit">Enviar Reclamação</button>
                </form>
                <div id="form-status"></div>
            </section>
        <?php endif; ?>

        <div class="rankings-grid">
            <section class="ranking-container"><h2>🏆 Melhores Linhas</h2><ol class="ranking-list"><?php foreach ($melhores_linhas as $linha): ?><li><span class="ranking-linha"><?= htmlspecialchars($linha['linha_onibus']) ?></span><span class="ranking-count"><?= $linha['total_reclamacoes'] ?> reclamações</span></li><?php endforeach; if (empty($melhores_linhas)): ?><p class="no-data">Ainda não há dados suficientes.</p><?php endif; ?></ol></section>
            <section class="ranking-container"><h2>💔 Piores Linhas</h2><ol class="ranking-list"><?php foreach ($piores_linhas as $linha): ?><li><span class="ranking-linha"><?= htmlspecialchars($linha['linha_onibus']) ?></span><span class="ranking-count"><?= $linha['total_reclamacoes'] ?> reclamações</span></li><?php endforeach; if (empty($piores_linhas)): ?><p class="no-data">Ainda não há dados suficientes.</p><?php endif; ?></ol></section>
        </div>

        <section class="reclamacoes-container">
            <h2>Últimas Reclamações Registradas</h2>
            <div id="lista-reclamacoes">
                <?php if (empty($reclamacoes_recentes)): ?><p class="no-data">Nenhuma reclamação registrada ainda.</p><?php else: ?><?php foreach ($reclamacoes_recentes as $reclamacao): ?><div class="reclamacao-card"><h3>Linha: <?= htmlspecialchars($reclamacao['linha_onibus']) ?></h3><p><strong>Tipo:</strong> <?= htmlspecialchars($reclamacao['tipo_reclamacao']) ?></p><p class="reclamacao-autor">Por: <strong><?= htmlspecialchars($reclamacao['autor']) ?></strong></p><p><?= nl2br(htmlspecialchars($reclamacao['descricao'])) ?></p><small>Registrado em: <?= $reclamacao['data_formatada'] ?></small></div><?php endforeach; ?><?php endif; ?>
            </div>
        </section>
    </main>
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-about"><h4>Sobre a Plataforma</h4><p>Um canal independente para passageiros registrarem, consultarem e darem visibilidade a problemas no transporte público. Acreditamos que dados transparentes são o primeiro passo para a mudança.</p></div>
            <div class="footer-links"><h4>Navegação</h4><ul><li><a href="#">Página Inicial</a></li><li><a href="#">Termos de Serviço</a></li><li><a href="#">Política de Privacidade</a></li><li><a href="#">Como Funciona</a></li></ul></div>
            <div class="footer-contact"><h4>Contato (Fictício)</h4><p>Avenida da Cidadania, 100<br>Centro, São Paulo - SP</p><p>contato@voz-do-passageiro.org</p><p>(11) 4004-0000</p></div>
            <div class="footer-social"><h4>Redes Sociais</h4><div class="social-icons"><a href="#" aria-label="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919-4.919C8.356 2.175 8.741 2.163 12 2.163m0-2.163C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.059-1.281.073-1.689.073-4.948s-.014-3.667-.072-4.947C21.727 2.69 19.305.273 14.947.072 13.667.014 13.259 0 12 0zm0 7.425c-2.53 0-4.575 2.045-4.575 4.575s2.045 4.575 4.575 4.575 4.575-2.045 4.575-4.575S14.53 7.425 12 7.425zm0 7.167c-1.423 0-2.592-1.169-2.592-2.592s1.169-2.592 2.592-2.592 2.592 1.169 2.592 2.592-1.169 2.592-2.592 2.592zm4.492-8.542c-.411 0-.746.335-.746.746s.335.746.746.746.746-.335.746-.746-.335-.746-.746-.746z"/></svg></a><a href="#" aria-label="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a><a href="#" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v2.385z"/></svg></a></div></div>
        </div>
        <div class="footer-bottom"><p>&copy; <?= date("Y") ?> Voz do Passageiro. Plataforma independente, feita por cidadãos para cidadãos.</p></div>
    </footer>

    <script src="script.js"></script>
    
    </body>
</html>
