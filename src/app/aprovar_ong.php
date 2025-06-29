<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();
require_auth('admin');

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("Erro de segurança ou requisição inválida."));
    exit();
}

require_once __DIR__ . '/../core/db_connect.php';

$ong_id = filter_input(INPUT_POST, 'ong_id', FILTER_VALIDATE_INT);

if (!$ong_id) {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("ID da ONG inválido."));
    exit();
}

$stmt = $conn->prepare("UPDATE tb_ong SET aprovado = 1 WHERE ong_id = ?");
$stmt->bind_param("i", $ong_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    // TODO: Adicionar lógica para notificar a ONG por e-mail que ela foi aprovada.
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("ONG aprovada com sucesso."));
} else {
    header("Location: /kindact/public/index.php?page=admin_dashboard&message=" . urlencode("Erro ao aprovar a ONG ou ela já estava aprovada."));
}

$stmt->close();
$conn->close();
exit();
?>