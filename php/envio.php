<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: login_voluntario.html");
    exit();
}

$ong_id = isset($_GET['ong_id']) ? (int)$_GET['ong_id'] : 0;
$voluntario_id = $_SESSION['user_id'];

if ($ong_id <= 0) {
    die("Invalid ONG ID.");
}

$stmt = $conn->prepare("INSERT INTO tb_candidatura (fk_voluntario_id, fk_ong_id, candidatura_data) VALUES (?, ?, NOW())");
$stmt->bind_param("ii", $voluntario_id, $ong_id);

if ($stmt->execute()) {
    header("Location: usuario.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>