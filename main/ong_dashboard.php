<?php
// Arquivo: kindact/main/ong_dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ong') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include '../php/db_connect.php';

$ong_id = $_SESSION['user_id'];

// Lógica para publicar nova oportunidade
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Código de validação e inserção de oportunidade, mantido o mesmo) ...
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_termino = $_POST['data_termino'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $cep = $_POST['cep'] ?? '';

    if (empty($titulo) || empty($descricao) || empty($data_inicio) || empty($data_termino) || empty($endereco) || empty($cep)) {
        header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Por favor, preencha todos os campos para publicar a oportunidade."));
        exit();
    }

    $stmt_insert = $conn->prepare("INSERT INTO tb_evento (evento_titulo, evento_descricao, evento_data_inicio, evento_data_termino, evento_endereco, evento_cep, fk_ong_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("ssssssi", $titulo, $descricao, $data_inicio, $data_termino, $endereco, $cep, $ong_id);
    if ($stmt_insert->execute()) {
        header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Oportunidade publicada com sucesso!"));
        exit();
    } else {
        header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Erro ao publicar oportunidade."));
        exit();
    }
    $stmt_insert->close();
}

// Consulta para listar as oportunidades criadas pela ONG
$eventos_sql = "SELECT evento_id, evento_titulo, evento_data_inicio FROM tb_evento WHERE fk_ong_id = ?";
$eventos_stmt = $conn->prepare($eventos_sql);
$eventos_stmt->bind_param("i", $ong_id);
$eventos_stmt->execute();
$eventos_result = $eventos_stmt->get_result();

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Área da ONG</title>
</head>
<body>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
            <nav class="main-nav">
                <a href="/kindact/php/logout.php" class="btn btn-secondary">Sair</a>
            </nav>
        </header>
        <main>
            <h2>Gerenciar Oportunidades e Candidatos</h2>
            <div id="message-container" style="margin-bottom: 20px;"></div>

            <section id="publicar-oportunidade">
                <h3>Publicar Nova Oportunidade</h3>
                <form action="/kindact/main/ong_dashboard.php" method="post">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="data_inicio">Data de Início:</label>
                        <input type="date" id="data_inicio" name="data_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="data_termino">Data de Término:</label>
                        <input type="date" id="data_termino" name="data_termino" required>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço:</label>
                        <input type="text" id="endereco" name="endereco" required>
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP:</label>
                        <input type="text" id="cep" name="cep" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </form>
            </section>

            <section id="oportunidades-publicadas" style="margin-top: 40px;">
                <h3>Minhas Oportunidades Publicadas</h3>
                <?php if ($eventos_result->num_rows > 0): ?>
                    <ul class="event-list">
                        <?php while ($evento = $eventos_result->fetch_assoc()): ?>
                            <li class="event-item">
                                <h4><?php echo htmlspecialchars($evento['evento_titulo']); ?></h4>
                                <p>Data: <?php echo htmlspecialchars(date('d/m/Y', strtotime($evento['evento_data_inicio']))); ?></p>
                                <div class="event-actions">
                                    <a href="/kindact/main/gerenciar_candidatos.php?evento_id=<?php echo $evento['evento_id']; ?>" class="btn btn-primary">Ver Candidatos</a>
                                    <a href="/kindact/main/gerenciar_oportunidade.php?evento_id=<?php echo $evento['evento_id']; ?>" class="btn btn-secondary">Editar</a>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Você ainda não publicou nenhuma oportunidade. Crie uma acima!</p>
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