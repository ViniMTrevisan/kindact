<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=form_cadastro_admin&message=" . urlencode("Erro de segurança."));
    exit();
}

require_once __DIR__ . '/../core/db_connect.php';

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = $_POST['password'] ?? '';

if (!$email || empty($senha)) {
    header("Location: /kindact/public/index.php?page=form_cadastro_admin&message=" . urlencode("Dados inválidos."));
    exit();
}

$stmt_check = $conn->prepare("SELECT admin_id FROM tb_admin WHERE admin_email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
if ($stmt_check->get_result()->num_rows > 0) {
    header("Location: /kindact/public/index.php?page=form_cadastro_admin&message=" . urlencode("Este email já está cadastrado."));
    exit();
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt_insert = $conn->prepare("INSERT INTO tb_admin (admin_email, admin_senha) VALUES (?, ?)");
$stmt_insert->bind_param("ss", $email, $senha_hash);

if ($stmt_insert->execute()) {
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Administrador cadastrado com sucesso!"));
} else {
    header("Location: /kindact/public/index.php?page=form_cadastro_admin&message=" . urlencode("Erro ao cadastrar."));
}
exit();
?>