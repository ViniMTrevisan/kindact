<?php
// Arquivo: remover_voluntario.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include 'db_connect.php';

$voluntario_id = $_GET['voluntario_id'] ?? '';

if (empty($voluntario_id)) {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("ID do voluntário não fornecido."));
    exit();
}

$stmt = $conn->prepare("DELETE FROM tb_voluntario WHERE voluntario_id = ?");
$stmt->bind_param("i", $voluntario_id);

if ($stmt->execute()) {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("Voluntário removido com sucesso."));
    exit();
} else {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("Erro ao remover voluntário."));
    exit();
}

$stmt->close();
$conn->close();
?>