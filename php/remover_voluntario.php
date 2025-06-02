<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login_admin.html");
    exit();
}

$voluntario_id = isset($_GET['voluntario_id']) ? (int)$_GET['voluntario_id'] : 0;

if ($voluntario_id <= 0) {
    die("Invalid volunteer ID.");
}

$stmt = $conn->prepare("DELETE FROM tb_voluntario WHERE voluntario_id = ?");
$stmt->bind_param("i", $voluntario_id);

if ($stmt->execute()) {
    header("Location: aprovacao_admin.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>