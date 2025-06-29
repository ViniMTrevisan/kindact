<?php
// /src/views/partials/header.php (VERSÃO FINAL E CORRIGIDA)
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/kindact/public/css/style.css?v=1.1">
    <title><?php echo isset($page_title) ? e($page_title) . ' - KindAct' : 'KindAct'; ?></title>
</head>
<body>
    <div class="container">
        <header class="header">
            <a href="/kindact/public/" class="logo">KindAct</a>
            <nav class="main-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    
                    <?php //-- Menu específico para VOLUNTÁRIO --// ?>
                    <?php if ($_SESSION['user_type'] == 'voluntario'): ?>
                        <a href="/kindact/public/index.php?page=voluntario_dashboard" class="btn btn-primary">Ver Oportunidades</a>
                        <a href="/kindact/public/index.php?page=minhas_candidaturas" class="btn btn-primary">Minhas Candidaturas</a>
                    
                    <?php //-- Menu específico para ONG --// ?>
                    <?php elseif ($_SESSION['user_type'] == 'ong'): ?>
                         <a href="/kindact/public/index.php?page=ong_dashboard" class="btn btn-primary">Meu Dashboard</a>
                    
                    <?php //-- Menu específico para ADMIN --// ?>
                    <?php elseif ($_SESSION['user_type'] == 'admin'): ?>
                         <a href="/kindact/public/index.php?page=admin_dashboard" class="btn btn-primary">Dashboard Admin</a>
                    <?php endif; ?>

                    <a href="/kindact/src/app/logout.php" class="btn btn-secondary">Sair</a>

                <?php else: // Para visitantes não logados ?>
                    <a href="/kindact/public/index.php?page=login" class="btn btn-primary">Login</a>
                <?php endif; ?>
            </nav>
        </header>
        <main>