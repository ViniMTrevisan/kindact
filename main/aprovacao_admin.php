<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /kindact/main/login_admin.html");
    exit();
}

include '../php/db_connect.php';

$ong_sql = "SELECT ong_id, ong_nome, ong_email, ong_cnpj, ong_area_atuacao FROM tb_ong WHERE aprovado = 0";
$ong_result = $conn->query($ong_sql);

$voluntario_sql = "SELECT voluntario_id, voluntario_nome, voluntario_email FROM tb_voluntario";
$voluntario_result = $conn->query($voluntario_sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css">
    <title>Aprovação - Admin</title>
</head>
<body>
    <a href="/kindact/main/admin_tela.html" class="back-link" aria-label="Voltar para a tela admin">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
        </header>
        <main>
            <h2>Aprovar ONGs</h2>
            <?php if ($ong_result->num_rows > 0): ?>
                <ul class="ong-list">
                    <?php while ($row = $ong_result->fetch_assoc()): ?>
                        <li class="ong-item">
                            <h3><?php echo htmlspecialchars($row['ong_nome']); ?></h3>
                            <p>Email: <?php echo htmlspecialchars($row['ong_email']); ?></p>
                            <p>CNPJ: <?php echo htmlspecialchars($row['ong_cnpj']); ?></p>
                            <p>Área de Atuação: <?php echo htmlspecialchars($row['ong_area_atuacao']); ?></p>
                            <a href="/kindact/main/aprovar_ong.html?ong_id=<?php echo $row['ong_id']; ?>" class="btn btn-primary">Aprovar</a>
                            <a href="/kindact/php/remover_ong.php?ong_id=<?php echo $row['ong_id']; ?>" class="btn btn-danger">Remover</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nenhuma ONG pendente de aprovação.</p>
            <?php endif; ?>

            <h2>Gerenciar Voluntários</h2>
            <?php if ($voluntario_result->num_rows > 0): ?>
                <ul class="voluntario-list">
                    <?php while ($row = $voluntario_result->fetch_assoc()): ?>
                        <li class="voluntario-item">
                            <h3><?php echo htmlspecialchars($row['voluntario_nome']); ?></h3>
                            <p>Email: <?php echo htmlspecialchars($row['voluntario_email']); ?></p>
                            <a href="/kindact/php/remover_voluntario.php?voluntario_id=<?php echo $row['voluntario_id']; ?>" class="btn btn-danger">Remover</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum voluntário encontrado.</p>
            <?php endif; ?>
        </main>
        <footer class="footer">
            <p class="footer-brand">KindAct</p>
            <p class="footer-text">Juntos, podemos fazer a diferença. Conecte-se, colabore e transforme!</p>
            <div class="footer-links">
                <a href="/kindact/main/termos.html" class="footer-link">Termos</a>
                <a href="/kindact/main/politica_privacidade.html" class="footer-link">Política de Privacidade</a>
            </div>
        </footer>
    </div>
    <script src="/kindact/js/script.js"></script>
</body>
</html>

<?php
$conn->close();
?>