<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: /kindact/main/login_voluntario.html");
    exit();
}

include '../php/db_connect.php';

$sql = "SELECT ong_id, ong_nome, ong_area_atuacao FROM tb_ong WHERE aprovado = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.0">
    <title>Selecionar ONGs</title>
</head>
<body>
    <a href="/kindact/main/usuario.html" class="back-link" aria-label="Voltar para a página de usuário">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
        </header>
        <main>
            <h2>Escolha uma ONG para se Candidatar</h2>
            <?php if ($result->num_rows > 0): ?>
                <ul class="ong-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="ong-item">
                            <h3><?php echo htmlspecialchars($row['ong_nome']); ?></h3>
                            <p>Área de Atuação: <?php echo htmlspecialchars($row['ong_area_atuacao']); ?></p>
                            <a href="/kindact/main/envio.html?ong_id=<?php echo $row['ong_id']; ?>" class="btn btn-primary">Candidatar-se</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nenhuma ONG aprovada encontrada.</p>
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