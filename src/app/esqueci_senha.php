<?php
require_once __DIR__ . '/../core/security.php';
require_once __DIR__ . '/../core/db_connect.php';
secure_session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=form_esqueci_senha&message=" . urlencode("Erro de segurança."));
    exit();
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    header("Location: /kindact/public/index.php?page=form_esqueci_senha&message=" . urlencode("Formato de e-mail inválido."));
    exit();
}

$success_message = "Se um e-mail correspondente for encontrado em nosso sistema, um link de recuperação será enviado.";

$stmt = $conn->prepare("SELECT email FROM ((SELECT ong_email as email FROM tb_ong WHERE ong_email = ?) UNION (SELECT voluntario_email as email FROM tb_voluntario WHERE voluntario_email = ?) UNION (SELECT admin_email as email FROM tb_admin WHERE admin_email = ?)) as user_emails");
$stmt->bind_param("sss", $email, $email, $email);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $token = bin2hex(random_bytes(32));
    $expires = new DateTime('now + 1 hour');
    $expires_at = $expires->format('Y-m-d H:i:s');

    $stmt_insert = $conn->prepare("INSERT INTO tb_password_resets (user_email, token, expires_at) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("sss", $email, $token, $expires_at);
    $stmt_insert->execute();
}

header("Location: /kindact/public/index.php?page=login&message=" . urlencode($success_message));
exit();
?>