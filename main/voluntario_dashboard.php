<?php
// Arquivo: kindact/main/voluntario_dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include '../php/db_connect.php';

// Consulta para obter ONGs aprovadas com descrição (se disponível)
$sql = "SELECT ong_id, ong_nome, ong_area_atuacao FROM tb_ong WHERE aprovado = 1";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Área do Voluntário</title>
</head>
<body>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
            <nav class="main-nav">
                <a href="/kindact/main/minhas_candidaturas.php" class="btn btn-secondary">Minhas Candidaturas</a>
                <a href="/kindact/php/logout.php" class="btn btn-secondary">Sair</a>
            </nav>
        </header>
        <main>
            <h2>ONGs Disponíveis</h2>
            <div id="message-container" style="margin-bottom: 20px;"></div>
            <p>Explore as ONGs disponíveis e veja suas oportunidades. Use a barra de pesquisa para encontrar uma ONG específica.</p>
            
            <?php if ($result->num_rows > 0): ?>
                <ul class="ong-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="ong-item">
                            <h3><?php echo htmlspecialchars($row['ong_nome']); ?></h3>
                            <p>Área de Atuação: <?php echo htmlspecialchars($row['ong_area_atuacao']); ?></p>
                            <a href="/kindact/main/detalhes_ong.php?ong_id=<?php echo $row['ong_id']; ?>" class="btn btn-primary">Ver Perfil e Oportunidades</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nenhuma ONG aprovada encontrada no momento.</p>
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