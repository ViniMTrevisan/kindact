<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();
require_auth('admin');

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("Erro de segurança."));
    exit();
}

require_once __DIR__ . '/../core/db_connect.php';
$voluntario_id = filter_input(INPUT_POST, 'voluntario_id', FILTER_VALIDATE_INT);

if (!$voluntario_id) {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("ID de voluntário inválido."));
    exit();
}

$stmt = $conn->prepare("DELETE FROM tb_voluntario WHERE voluntario_id = ?");
$stmt->bind_param("i", $voluntario_id);

if ($stmt->execute()) {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("Voluntário removido com sucesso."));
} else {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("Erro ao remover voluntário."));
}
exit();
?>