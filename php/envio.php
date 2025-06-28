<?php
// Arquivo: kindact/php/envio.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voluntario_id = $_SESSION['user_id'];
    $evento_id = $_POST['evento_id'] ?? '';

    if (empty($evento_id)) {
        header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("ID da oportunidade não fornecido."));
        exit();
    }

    // --- Nova Verificação: Evita candidaturas duplicadas ---
    $stmt_check = $conn->prepare("SELECT candidatura_id FROM tb_candidatura WHERE fk_voluntario_id = ? AND fk_evento_id = ?");
    $stmt_check->bind_param("ii", $voluntario_id, $evento_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("Você já se candidatou para esta oportunidade."));
        exit();
    }
    $stmt_check->close();

    // Inserção da candidatura com o ID do evento
    $stmt = $conn->prepare("INSERT INTO tb_candidatura (fk_voluntario_id, fk_evento_id, status) VALUES (?, ?, 'pendente')");
    $stmt->bind_param("ii", $voluntario_id, $evento_id);

    if ($stmt->execute()) {
        header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("Candidatura enviada com sucesso!"));
        exit();
    } else {
        header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("Erro ao enviar candidatura."));
        exit();
    }

    $stmt->close();
} else {
    header("Location: /kindact/main/voluntario_dashboard.php?message=" . urlencode("Método não permitido. Use POST."));
    exit();
}
$conn->close();
?>