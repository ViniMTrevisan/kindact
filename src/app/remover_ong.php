<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();
require_auth('admin');

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("Erro de segurança."));
    exit();
}

require_once __DIR__ . '/../core/db_connect.php';
$ong_id = filter_input(INPUT_POST, 'ong_id', FILTER_VALIDATE_INT);

if (!$ong_id) {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("ID da ONG inválido."));
    exit();
}

$stmt = $conn->prepare("DELETE FROM tb_ong WHERE ong_id = ?");
$stmt->bind_param("i", $ong_id);

if ($stmt->execute()) {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("ONG removida com sucesso."));
} else {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("Erro ao remover ONG."));
}
exit();
?>