<?php
// Arquivo: kindact/main/detalhes_ong.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include '../php/db_connect.php';

$ong_id = $_GET['ong_id'] ?? null;

if (is_null($ong_id)) {
    header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("ONG não especificada."));
    exit();
}

// Consulta para obter os detalhes da ONG
$stmt_ong = $conn->prepare("SELECT ong_nome, ong_email, ong_area_atuacao, ong_descricao FROM tb_ong WHERE ong_id = ? AND aprovado = 1");
$stmt_ong->bind_param("i", $ong_id);
$stmt_ong->execute();
$ong_result = $stmt_ong->get_result();

if ($ong_result->num_rows === 0) {
    header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("ONG não encontrada ou não aprovada."));
    exit();
}
$ong_data = $ong_result->fetch_assoc();
$stmt_ong->close();

// Consulta para obter as oportunidades da ONG
$stmt_eventos = $conn->prepare("SELECT evento_id, evento_titulo, evento_descricao, evento_data_inicio FROM tb_evento WHERE fk_ong_id = ? ORDER BY evento_data_inicio DESC");
$stmt_eventos->bind_param("i", $ong_id);
$stmt_eventos->execute();
$eventos_result = $stmt_eventos->get_result();

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title><?php echo htmlspecialchars($ong_data['ong_nome']); ?> - Perfil</title>
</head>
<body>
    <a href="/kindact/main/voluntario_dashboard.php" class="back-link" aria-label="Voltar para o dashboard">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
            <nav class="main-nav">
                <a href="/kindact/php/logout.php" class="btn btn-secondary">Sair</a>
            </nav>
        </header>
        <main>
            <h2>Perfil da ONG: <?php echo htmlspecialchars($ong_data['ong_nome']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($ong_data['ong_email']); ?></p>
            <p><strong>Área de Atuação:</strong> <?php echo htmlspecialchars($ong_data['ong_area_atuacao']); ?></p>
            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($ong_data['ong_descricao'] ?: 'Nenhuma descrição fornecida.'); ?></p>

            <section id="oportunidades-ong" style="margin-top: 40px;">
                <h3>Oportunidades de Voluntariado com esta ONG</h3>
                <?php if ($eventos_result->num_rows > 0): ?>
                    <ul class="event-list">
                        <?php while ($evento = $eventos_result->fetch_assoc()): ?>
                            <li class="event-item">
                                <h4><?php echo htmlspecialchars($evento['evento_titulo']); ?></h4>
                                <p><?php echo htmlspecialchars(substr($evento['evento_descricao'], 0, 100)); ?>...</p>
                                <p>Data: <?php echo htmlspecialchars(date('d/m/Y', strtotime($evento['evento_data_inicio']))); ?></p>
                                <a href="/kindact/main/detalhes_oportunidade.php?evento_id=<?php echo $evento['evento_id']; ?>" class="btn btn-primary">Ver Detalhes e Candidatar-se</a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Esta ONG não possui oportunidades publicadas no momento.</p>
                <?php endif; ?>
            </section>
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