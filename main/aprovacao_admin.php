<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login_admin.html");
    exit();
}

$ongs = json_decode(file_get_contents('get_ongs.php'), true);
if (!$ongs) {
    $ongs = [];
}

$voluntarios = json_decode(file_get_contents('get_voluntarios.php'), true);
if (!$voluntarios) {
    $voluntarios = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Aprovação - Admin</title>
</head>
<body>
    <a href="admin_tela.html" class="back-link" aria-label="Voltar para a página anterior">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="index.html" class="logo">KindAct</a>
            <a href="logout.php" class="logout-link">Sair</a>
            <span class="user-type">Admin</span>
        </header>
        <main>
            <h2>Aprovação de Cadastros</h2>
            <section class="aprovar">
                <h3>ONGs Pendentes</h3>
                <ul class="ong-list">
                    <?php if (empty($ongs)): ?>
                        <li><p>Nenhuma ONG pendente.</p></li>
                    <?php else: ?>
                        <?php foreach ($ongs as $ong): ?>
                            <?php if (!$ong['aprovado']): ?>
                                <li class="ong-item">
                                    <h3><?php echo htmlspecialchars($ong['ong_nome']); ?></h3>
                                    <p>Área de Atuação: <?php echo htmlspecialchars($ong['ong_area_atuacao'] ?: 'Não informado'); ?></p>
                                    <a href="aprovar_ong.html?ong_id=<?php echo (int)$ong['ong_id']; ?>" class="btn">Aprovar ONG</a>
                                    <a href="remover_ong.php?ong_id=<?php echo (int)$ong['ong_id']; ?>" class="btn btn-secondary remover">Remover ONG</a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </section>
            <section class="remover">
                <h3>Voluntários</h3>
                <ul class="voluntario-list">
                    <?php if (empty($voluntarios)): ?>
                        <li><p>Nenhum voluntário disponível.</p></li>
                    <?php else: ?>
                        <?php foreach ($voluntarios as $voluntario): ?>
                            <li>
                                <h3><?php echo htmlspecialchars($voluntario['voluntario_nome']); ?></h3>
                                <p>Cidade: <?php echo htmlspecialchars($voluntario['voluntario_cidade'] ?: 'Não informado'); ?></p>
                                <a href="remover_voluntario.php?voluntario_id=<?php echo (int)$voluntario['voluntario_id']; ?>" class="btn btn-secondary remover">Remover Voluntário</a>
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