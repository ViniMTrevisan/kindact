<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();
require_auth('ong');

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Erro de segurança."));
    exit();
}

require_once __DIR__ . '/../core/db_connect.php';

$voluntario_id = filter_input(INPUT_POST, 'voluntario_id', FILTER_VALIDATE_INT);
$evento_id = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);
$ong_id = $_SESSION['user_id'];

if (!$voluntario_id || !$evento_id) {
    header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Dados inválidos."));
    exit();
}

// Atualiza o status da candidatura para 'contactado'
$stmt = $conn->prepare("UPDATE tb_candidatura SET status = 'contactado' WHERE fk_voluntario_id = ? AND fk_evento_id IN (SELECT evento_id FROM tb_evento WHERE evento_id = ? AND fk_ong_id = ?)");
$stmt->bind_param("iii", $voluntario_id, $evento_id, $ong_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    // TODO: Adicionar lógica de notificação por email para o voluntário aqui.
    header("Location: /kindact/public/index.php?page=gerenciar_candidatos&evento_id={$evento_id}&message=" . urlencode("Voluntário marcado como contactado!"));
} else {
    header("Location: /kindact/public/index.php?page=gerenciar_candidatos&evento_id={$evento_id}&message=" . urlencode("Erro ao processar o contato."));
}
exit();
?>