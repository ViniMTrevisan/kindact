<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ong') {
    header("Location: login_ong.html");
    exit();
}

$voluntario_id = isset($_GET['voluntario_id']) ? (int)$_GET['voluntario_id'] : 0;
$ong_id = $_SESSION['user_id'];

if ($voluntario_id <= 0) {
    die("Invalid volunteer ID.");
}

$stmt = $conn->prepare("INSERT INTO tb_contato (fk_voluntario_id, fk_ong_id, contato_data) VALUES (?, ?, NOW())");
$stmt->bind_param("ii", $voluntario_id, $ong_id);

if ($stmt->execute()) {
    header("Location: contato_confirmado.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>