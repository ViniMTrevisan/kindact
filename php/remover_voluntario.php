<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /kindact/main/login_admin.html");
    exit();
}

include 'db_connect.php';

$voluntario_id = $_GET['voluntario_id'] ?? '';

if (empty($voluntario_id)) {
    die("ID do voluntário não fornecido.");
}

$stmt = $conn->prepare("DELETE FROM tb_voluntario WHERE voluntario_id = ?");
$stmt->bind_param("i", $voluntario_id);

if ($stmt->execute()) {
    header("Location: /kindact/main/aprovacao_admin.php");
    exit();
} else {
    echo "Erro ao remover voluntário.";
}

$stmt->close();
$conn->close();
?>