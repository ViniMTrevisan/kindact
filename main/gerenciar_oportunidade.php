<?php
// Arquivo: kindact/main/gerenciar_oportunidade.php
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

// Verifica se a oportunidade pertence à ONG logada
$stmt_check = $conn->prepare("SELECT evento_titulo, evento_descricao, evento_data_inicio, evento_data_termino, evento_endereco, evento_cep FROM tb_evento WHERE evento_id = ? AND fk_ong_id = ?");
$stmt_check->bind_param("ii", $evento_id, $ong_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Você não tem permissão para gerenciar esta oportunidade."));
    exit();
}
$evento_data = $result_check->fetch_assoc();
$stmt_check->close();

// Lógica para processar a edição ou exclusão
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'editar') {
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $data_inicio = $_POST['data_inicio'] ?? '';
        $data_termino = $_POST['data_termino'] ?? '';
        $endereco = $_POST['endereco'] ?? '';
        $cep = $_POST['cep'] ?? '';
        
        // Validação de dados de entrada
        if (empty($titulo) || empty($descricao) || empty($data_inicio) || empty($data_termino) || empty($endereco) || empty($cep)) {
            $message = "Por favor, preencha todos os campos.";
        } else {
            $stmt_update = $conn->prepare("UPDATE tb_evento SET evento_titulo = ?, evento_descricao = ?, evento_data_inicio = ?, evento_data_termino = ?, evento_endereco = ?, evento_cep = ? WHERE evento_id = ? AND fk_ong_id = ?");
            $stmt_update->bind_param("ssssssii", $titulo, $descricao, $data_inicio, $data_termino, $endereco, $cep, $evento_id, $ong_id);
            if ($stmt_update->execute()) {
                header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Oportunidade atualizada com sucesso!"));
                exit();
            } else {
                $message = "Erro ao atualizar a oportunidade.";
            }
            $stmt_update->close();
        }

    } elseif ($action === 'excluir') {
        $stmt_delete = $conn->prepare("DELETE FROM tb_evento WHERE evento_id = ? AND fk_ong_id = ?");
        $stmt_delete->bind_param("ii", $evento_id, $ong_id);
        if ($stmt_delete->execute()) {
            header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Oportunidade removida com sucesso."));
            exit();
        } else {
            $message = "Erro ao remover a oportunidade.";
        }
        $stmt_delete->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Gerenciar Oportunidade</title>
</head>
<body>
    <a href="/kindact/main/ong_dashboard.php" class="back-link" aria-label="Voltar para a página da ONG">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
        </header>
        <main>
            <h2>Gerenciar Oportunidade</h2>
            <div id="message-container" style="margin-bottom: 20px;">
                <?php if (isset($message)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
            </div>
            
            <section id="editar-oportunidade">
                <h3>Editar Oportunidade</h3>
                <form action="/kindact/main/gerenciar_oportunidade.php?evento_id=<?php echo $evento_id; ?>" method="post">
                    <input type="hidden" name="action" value="editar">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($evento_data['evento_titulo']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($evento_data['evento_descricao']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="data_inicio">Data de Início:</label>
                        <input type="date" id="data_inicio" name="data_inicio" value="<?php echo htmlspecialchars($evento_data['evento_data_inicio']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="data_termino">Data de Término:</label>
                        <input type="date" id="data_termino" name="data_termino" value="<?php echo htmlspecialchars($evento_data['evento_data_termino']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço:</label>
                        <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($evento_data['evento_endereco']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP:</label>
                        <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($evento_data['evento_cep']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </section>

            <section id="excluir-oportunidade" style="margin-top: 40px;">
                <h3>Excluir Oportunidade</h3>
                <form action="/kindact/main/gerenciar_oportunidade.php?evento_id=<?php echo $evento_id; ?>" method="post">
                    <input type="hidden" name="action" value="excluir">
                    <button type="submit" class="btn btn-danger remover">Excluir Oportunidade</button>
                </form>
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