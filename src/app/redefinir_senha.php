<?php
require_once __DIR__ . '/../core/security.php';
require_once __DIR__ . '/../core/db_connect.php';
secure_session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Erro de segurança."));
    exit();
}

$token = $_POST['token'] ?? '';
$nova_senha = $_POST['password'] ?? '';
$confirma_senha = $_POST['password_confirm'] ?? '';

if (empty($token) || empty($nova_senha) || $nova_senha !== $confirma_senha) {
    $redirect_url = "/kindact/public/index.php?page=form_redefinir_senha&token=" . urlencode($token) . "&message=" . urlencode("As senhas não coincidem ou estão vazias.");
    header("Location: " . $redirect_url);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM tb_password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Token inválido ou expirado."));
    exit();
}

$reset_data = $result->fetch_assoc();
$email = $reset_data['user_email'];
$senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

$conn->begin_transaction();
try {
    $stmt_update_ong = $conn->prepare("UPDATE tb_ong SET ong_senha = ? WHERE ong_email = ?");
    $stmt_update_ong->bind_param("ss", $senha_hash, $email);
    $stmt_update_ong->execute();

    $stmt_update_vol = $conn->prepare("UPDATE tb_voluntario SET voluntario_senha = ? WHERE voluntario_email = ?");
    $stmt_update_vol->bind_param("ss", $senha_hash, $email);
    $stmt_update_vol->execute();
    
    $stmt_update_admin = $conn->prepare("UPDATE tb_admin SET admin_senha = ? WHERE admin_email = ?");
    $stmt_update_admin->bind_param("ss", $senha_hash, $email);
    $stmt_update_admin->execute();

    $stmt_delete = $conn->prepare("DELETE FROM tb_password_resets WHERE user_email = ?");
    $stmt_delete->bind_param("s", $email);
    $stmt_delete->execute();

    $conn->commit();
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Senha redefinida com sucesso!"));
} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Erro ao atualizar a senha."));
}
exit();
?>