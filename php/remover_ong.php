<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /kindact/main/login_admin.html");
    exit();
}

include 'db_connect.php';

$ong_id = $_GET['ong_id'] ?? '';

if (empty($ong_id)) {
    die("ID da ONG não fornecido.");
}

$stmt = $conn->prepare("DELETE FROM tb_ong WHERE ong_id = ?");
$stmt->bind_param("i", $ong_id);

if ($stmt->execute()) {
    header("Location: /kindact/main/aprovacao_admin.php");
    exit();
} else {
    echo "Erro ao remover ONG.";
}

$stmt->close();
$conn->close();
?>