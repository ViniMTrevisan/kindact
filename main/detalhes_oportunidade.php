<?php
// Arquivo: kindact/main/detalhes_oportunidade.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include '../php/db_connect.php';

$evento_id = $_GET['evento_id'] ?? null;

if (is_null($evento_id)) {
    header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("Oportunidade não encontrada."));
    exit();
}

// Consulta para obter os detalhes do evento
$stmt = $conn->prepare("SELECT * FROM tb_evento WHERE evento_id = ?");
$stmt->bind_param("i", $evento_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("Oportunidade não encontrada."));
    exit();
}

$evento = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.0">
    <title>Detalhes da Oportunidade</title>
</head>
<body>
    <a href="/kindact/main/voluntario_dashboard.php" class="back-link" aria-label="Voltar para as oportunidades">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
        </header>
        <main>
            <h2>Detalhes da Oportunidade</h2>
            <div class="opportunity-details">
                <h3><?php echo htmlspecialchars($evento['evento_titulo']); ?></h3>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($evento['evento_descricao']); ?></p>
                <p><strong>Data de Início:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($evento['evento_data_inicio']))); ?></p>
                <p><strong>Data de Término:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($evento['evento_data_termino']))); ?></p>
                <p><strong>Endereço:</strong> <?php echo htmlspecialchars($evento['evento_endereco']); ?></p>
                <p><strong>CEP:</strong> <?php echo htmlspecialchars($evento['evento_cep']); ?></p>
            </div>
            
            <form action="/kindact/php/envio.php" method="post" style="margin-top: 20px;">
                <input type="hidden" name="evento_id" value="<?php echo $evento['evento_id']; ?>">
                <button type="submit" class="btn btn-primary">Candidatar-se a esta Oportunidade</button>
            </form>
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