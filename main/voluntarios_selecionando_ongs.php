<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: login_voluntario.html");
    exit();
}

$ongs = json_decode(file_get_contents('get_ongs.php'), true);
if (!$ongs) {
    $ongs = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <title>Voluntários - Selecionando ONGs</title>
</head>
<body>
    <a href="usuario.html" class="back-link" aria-label="Voltar para a página anterior">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="index.html" class="logo">KindAct</a>
            <a href="logout.php" class="logout-link">Sair</a>
        </header>
        <main>
            <h2>Selecionar ONG</h2>
            <section class="ong">
                <h3>Lista de ONGs</h3>
                <ul class="ong-list">
                    <?php if (empty($ongs)): ?>
                        <li><p>Nenhuma ONG disponível no momento.</p></li>
                    <?php else: ?>
                        <?php foreach ($ongs as $ong): ?>
                            <li class="ong-item">
                                <h3><?php echo htmlspecialchars($ong['ong_nome']); ?></h3>
                                <p>Área de Atuação: <?php echo htmlspecialchars($ong['ong_area_atuacao'] ?: 'Não informado'); ?></p>
                                <a href="envio.html?ong_id=<?php echo (int)$ong['ong_id']; ?>" class="btn">Selecionar</a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </section>
        </main>
        <footer class="footer">
            <p class="footer-brand">KindAct</p>
            <p class="footer-text">Juntos, podemos fazer a diferença. Conecte-se, colabore e transforme!</p>
            <div class="footer-links">
                <a href="termos.html" class="footer-link">Termos</a>
                <a href="politica_privacidade.html" class="footer-link">Política de Privacidade</a>
            </div>
        </footer>
    </div>
    <script src="/js/script.js"></script>
</body>
</html>