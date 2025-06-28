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
                    <a href="/kindact/src/app/logout.php" class="btn btn-secondary">Sair</a>
                <?php endif; ?>
            </nav>
        </header>
        <main>