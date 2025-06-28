<?php
// Arquivo: kindact/main/minhas_candidaturas.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include '../php/db_connect.php';

$voluntario_id = $_SESSION['user_id'];

// Consulta para listar as candidaturas do voluntário com detalhes do evento e da ONG
$sql = "SELECT c.status, e.evento_titulo, o.ong_nome, o.ong_email
        FROM tb_candidatura c
        JOIN tb_evento e ON c.fk_evento_id = e.evento_id
        JOIN tb_ong o ON e.fk_ong_id = o.ong_id
        WHERE c.fk_voluntario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $voluntario_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.0">
    <title>Minhas Candidaturas</title>
</head>
<body>
    <a href="/kindact/main/voluntario_dashboard.php" class="back-link" aria-label="Voltar para a área do voluntário">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
            <nav class="main-nav">
                <a href="/kindact/php/logout.php" class="btn btn-secondary">Sair</a>
            </nav>
        </header>
        <main>
            <h2>Minhas Candidaturas</h2>
            <?php if ($result->num_rows > 0): ?>
                <ul class="candidatura-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="candidatura-item">
                            <h4>Oportunidade: <?php echo htmlspecialchars($row['evento_titulo']); ?></h4>
                            <p><strong>ONG:</strong> <?php echo htmlspecialchars($row['ong_nome']); ?></p>
                            <p><strong>Email da ONG:</strong> <?php echo htmlspecialchars($row['ong_email']); ?></p>
                            <p><strong>Status:</strong> <span class="status-badge status-<?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Você ainda não se candidatou a nenhuma oportunidade.</p>
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
$stmt->close();
$conn->close();
?>