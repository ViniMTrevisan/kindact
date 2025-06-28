<?php
// Arquivo: /kindact/php/remover_ong.php
include 'security.php';
secure_session_start();
require_auth('admin');

// Validação do Token CSRF
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("Erro de segurança ou requisição inválida."));
    exit();
}

include 'db_connect.php';

$ong_id = $_POST['ong_id'] ?? '';

if (empty($ong_id)) {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("ID da ONG não fornecido."));
    exit();
}

$stmt = $conn->prepare("DELETE FROM tb_ong WHERE ong_id = ?");
$stmt->bind_param("i", $ong_id);

if ($stmt->execute()) {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("ONG removida com sucesso."));
} else {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("Erro ao remover ONG."));
}

$stmt->close();
$conn->close();
exit();
?>