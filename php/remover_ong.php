<?php
// Arquivo: remover_ong.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include 'db_connect.php';

$ong_id = $_GET['ong_id'] ?? '';

if (empty($ong_id)) {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("ID da ONG não fornecido."));
    exit();
}

$stmt = $conn->prepare("DELETE FROM tb_ong WHERE ong_id = ?");
$stmt->bind_param("i", $ong_id);

if ($stmt->execute()) {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("ONG removida com sucesso."));
    exit();
} else {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("Erro ao remover ONG."));
    exit();
}

$stmt->close();
$conn->close();
?>