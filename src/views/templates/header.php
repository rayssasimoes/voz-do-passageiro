<?php require_once __DIR__ . '/../../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Voz do Passageiro'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/header_style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/footer_style.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/<?php echo $page_css; ?>">
    <?php endif; ?>
</head>
<body>
    <header class="main-header">
        <div class="header-content container">
            <a href="<?php echo BASE_URL; ?>/index.php" class="logo">Voz do Passageiro</a>
            <button class="hamburger">&#9776;</button>
            <nav class="main-nav">
                <ul>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>/minhas_reclamacoes.php">Minhas Reclamações</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/meu_perfil.php">Meu Perfil</a></li>
                        <li><a id="logout-btn" href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-secondary">Sair</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-primary">Entrar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content-area">
        <div class="container">