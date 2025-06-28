<?php
// Arquivo: kindact/main/gerenciar_candidatos.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ong') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include '../php/db_connect.php';

$ong_id = $_SESSION['user_id'];
$evento_id = $_GET['evento_id'] ?? null;

if (is_null($evento_id)) {
    header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Oportunidade não especificada."));
    exit();
}

// Verifica se a oportunidade pertence à ONG logada (controle de acesso)
$check_stmt = $conn->prepare("SELECT fk_ong_id FROM tb_evento WHERE evento_id = ?");
$check_stmt->bind_param("i", $evento_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
if ($check_result->num_rows === 0 || $check_result->fetch_assoc()['fk_ong_id'] != $ong_id) {
    header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Você não tem permissão para gerenciar esta oportunidade."));
    exit();
}
$check_stmt->close();

// Consulta para obter os voluntários que se candidataram a este evento
$sql = "SELECT v.voluntario_id, v.voluntario_nome, v.voluntario_email, c.status
        FROM tb_candidatura c
        JOIN tb_voluntario v ON c.fk_voluntario_id = v.voluntario_id
        WHERE c.fk_evento_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $evento_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Gerenciar Candidatos</title>
</head>
<body>
    <a href="/kindact/main/ong_dashboard.php" class="back-link" aria-label="Voltar para a página da ONG">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
        </header>
        <main>
            <h2>Candidatos para a Oportunidade</h2>
            <div id="message-container" style="margin-bottom: 20px;"></div>
            <?php if ($result->num_rows > 0): ?>
                <ul class="voluntario-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="voluntario-item">
                            <h4><?php echo htmlspecialchars($row['voluntario_nome']); ?></h4>
                            <p>Email: <?php echo htmlspecialchars($row['voluntario_email']); ?></p>
                            <p>Status: <span class="status-badge status-<?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></p>
                            <form action="/kindact/php/processar_contato.php" method="post" style="display:inline;">
                                <input type="hidden" name="voluntario_id" value="<?php echo $row['voluntario_id']; ?>">
                                <input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
                                <button type="submit" class="btn btn-primary">Contactar Voluntário</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum voluntário se candidatou para esta oportunidade ainda.</p>
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