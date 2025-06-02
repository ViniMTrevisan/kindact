<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login_admin.html");
    exit();
}

$ong_id = isset($_GET['ong_id']) ? (int)$_GET['ong_id'] : 0;

if ($ong_id <= 0) {
    die("Invalid ONG ID.");
}

$stmt = $conn->prepare("DELETE FROM tb_ong WHERE ong_id = ?");
$stmt->bind_param("i", $ong_id);

if ($stmt->execute()) {
    header("Location: aprovacao_admin.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>