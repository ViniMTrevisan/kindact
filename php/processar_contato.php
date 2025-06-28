<?php
// Arquivo: processar_contato.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ong') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voluntario_id = $_POST['voluntario_id'] ?? '';
    $ong_id = $_SESSION['user_id'];

    if (empty($voluntario_id)) {
        header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("ID do voluntário não fornecido."));
        exit();
    }

    // Altera o status da candidatura para 'contactado'
    $stmt = $conn->prepare("UPDATE tb_candidatura SET status = 'contactado' WHERE fk_voluntario_id = ? AND fk_ong_id = ?");
    $stmt->bind_param("ii", $voluntario_id, $ong_id);

    if ($stmt->execute()) {
        header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Voluntário contactado com sucesso!"));
        exit();
    } else {
        header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Erro ao processar contato."));
        exit();
    }

    $stmt->close();
} else {
    header("Location: /kindact/main/ong_dashboard.php?message=" . urlencode("Método não permitido. Use POST."));
    exit();
}
$conn->close();
?>