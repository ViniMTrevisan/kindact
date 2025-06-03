<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ong') {
    header("Location: /kindact/main/login_ong.html");
    exit();
}

include '../php/db_connect.php';

$ong_id = $_SESSION['user_id'];
$sql = "SELECT v.voluntario_id, v.voluntario_nome, v.voluntario_email 
        FROM tb_candidatura c 
        JOIN tb_voluntario v ON c.fk_voluntario_id = v.voluntario_id 
        WHERE c.fk_ong_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ong_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;4
00;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css">
    <title>Publicar Oportunidade - ONG</title>
</head>
<body>
    <a href="/kindact/main/index.html" class="back-link" aria-label="Voltar para a página inicial">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
        </header>
        <main>
            <h2>Publicar Oportunidade e Gerenciar Candidatos</h2>
            <section>
                <h3>Publicar Nova Oportunidade</h3>
                <form action="/kindact/main/publicacao_ong.php" method="post">
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
                    <button type="submit" class="btn">Publicar</button>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $titulo = $_POST['titulo'] ?? '';
                    $descricao = $_POST['descricao'] ?? '';
                    $data_inicio = $_POST['data_inicio'] ?? '';
                    $data_termino = $_POST['data_termino'] ?? '';
                    $endereco = $_POST['endereco'] ?? '';
                    $cep = $_POST['cep'] ?? '';

                    $stmt_insert = $conn->prepare("INSERT INTO tb_evento (evento_titulo, evento_descricao, evento_data_inicio, evento_data_termino, evento_endereco, evento_cep, fk_ong_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt_insert->bind_param("ssssssi", $titulo, $descricao, $data_inicio, $data_termino, $endereco, $cep, $ong_id);
                    if ($stmt_insert->execute()) {
                        echo "<p>Oportunidade publicada com sucesso!</p>";
                    } else {
                        echo "<p>Erro ao publicar oportunidade.</p>";
                    }
                    $stmt_insert->close();
                }
                ?>
            </section>

            <section>
                <h3>Voluntários Candidatos</h3>
                <?php if ($result->num_rows > 0): ?>
                    <ul class="voluntario-list">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <li class="voluntario-item">
                                <h4><?php echo htmlspecialchars($row['voluntario_nome']); ?></h4>
                                <p>Email: <?php echo htmlspecialchars($row['voluntario_email']); ?></p>
                                <a href="/kindact/main/analise.html?voluntario_id=<?php echo $row['voluntario_id']; ?>" class="btn btn-primary">Analisar</a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Nenhum voluntário candidato encontrado.</p>
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

<?php
$stmt->close();
$conn->close();
?>