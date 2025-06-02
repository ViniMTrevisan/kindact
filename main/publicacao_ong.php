<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ong') {
    header("Location: login_ong.html");
    exit();
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
    <link rel="stylesheet" href="/css/style.css">
    <title>Publicação - ONG</title>
</head>
<body>
    <a href="index.html" class="back-link" aria-label="Voltar para a página inicial">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="index.html" class="logo">KindAct</a>
            <a href="logout.php" class="logout-link">Sair</a>
        </header>
        <main>
            <h2>Publicação de Oportunidade</h2>
            <section class="publicar">
                <h3>Publicar Oferta</h3>
                <form action="publicacao_ong.php" method="post">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="start-date">Data de Início:</label>
                        <input type="date" id="start-date" name="start-date" required>
                    </div>
                    <div class="form-group">
                        <label for="end-date">Data de Término:</label>
                        <input type="date" id="end-date" name="end-date" required>
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP:</label>
                        <input type="text" id="cep" name="cep" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Endereço:</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                    <button type="submit" class="btn">Publicar</button>
                </form>
            </section>
            <section class="analisar">
                <h3>Analisar Candidatos</h3>
                <ul class="candidate-list">
                    <?php if (empty($voluntarios)): ?>
                        <li><p>Nenhum candidato disponível.</p></li>
                    <?php else: ?>
                        <?php foreach ($voluntarios as $voluntario): ?>
                            <li><a href="analise.html?voluntario_id=<?php echo (int)$voluntario['voluntario_id']; ?>" class="btn"><?php echo htmlspecialchars($voluntario['voluntario_nome']); ?></a></li>
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

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $evento_titulo = $_POST['title'];
    $evento_descricao = $_POST['description'];
    $evento_data_inicio = $_POST['start-date'];
    $evento_data_termino = $_POST['end-date'];
    $evento_endereco = $_POST['address'];
    $evento_cep = $_POST['cep'];
    $fk_ong_id = $_SESSION['user_id'];

    $evento_logradouro = $evento_endereco;
    $evento_numero = '';
    $evento_bairro = '';
    $evento_complemento = '';
    $evento_cidade = '';
    $evento_uf = '';

    $stmt = $conn->prepare("INSERT INTO tb_evento (evento_titulo, evento_descricao, evento_data_inicio, evento_data_termino, evento_endereco, evento_cep, evento_logradouro, evento_numero, evento_bairro, evento_complemento, evento_cidade, evento_uf, fk_ong_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssi", $evento_titulo, $evento_descricao, $evento_data_inicio, $evento_data_termino, $evento_endereco, $evento_cep, $evento_logradouro, $evento_numero, $evento_bairro, $evento_complemento, $evento_cidade, $evento_uf, $fk_ong_id);

    if ($stmt->execute()) {
        header("Location: publicacao_ong.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>