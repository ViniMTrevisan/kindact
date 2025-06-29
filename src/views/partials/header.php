<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/kindact/public/css/style.css?v=1.4">
    <title><?php echo isset($page_title) ? e($page_title) . ' - KindAct' : 'KindAct'; ?></title>
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="/kindact/public/" class="logo">KindAct</a>
            <nav class="main-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    
                    <?php if ($_SESSION['user_type'] == 'voluntario'): ?>
                        <a href="/kindact/public/index.php?page=voluntario_dashboard" class="btn btn-azul">Ver Oportunidades</a>
                        <a href="/kindact/public/index.php?page=minhas_candidaturas" class="btn btn-azul">Minhas Candidaturas</a>
                    
                    <?php elseif ($_SESSION['user_type'] == 'ong'): ?>
                         <a href="/kindact/public/index.php?page=ong_dashboard" class="btn btn-primary">Meu Dashboard</a>
                    
                    <?php elseif ($_SESSION['user_type'] == 'admin'): ?>
                         <a href="/kindact/public/index.php?page=admin_dashboard" class="btn btn-primary">Dashboard Admin</a>
                    <?php endif; ?>

                    <a href="/kindact/src/app/logout.php" class="btn btn-secondary">Sair</a>

                <?php else: ?>
                    <a href="/kindact/public/index.php?page=login" class="btn btn-primary">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container content-card">