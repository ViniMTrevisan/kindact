<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=form_cadastro_voluntario&message=" . urlencode("Erro de segurança."));
    exit();
}

require_once __DIR__ . '/../core/db_connect.php';

$nome = trim($_POST['nome'] ?? '');
$telefone = preg_replace('/[^0-9]/', '', $_POST['telefone'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$cep = trim($_POST['cep'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$senha = $_POST['password'] ?? '';

if (empty($nome) || empty($telefone) || !$email || empty($cep) || empty($endereco) || empty($senha)) {
    header("Location: /kindact/public/index.php?page=form_cadastro_voluntario&message=" . urlencode("Todos os campos obrigatórios devem ser preenchidos corretamente."));
    exit();
}

$stmt_check = $conn->prepare("SELECT voluntario_id FROM tb_voluntario WHERE voluntario_email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
if ($stmt_check->get_result()->num_rows > 0) {
    header("Location: /kindact/public/index.php?page=form_cadastro_voluntario&message=" . urlencode("Este email já está em uso."));
    exit();
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt_insert = $conn->prepare("INSERT INTO tb_voluntario (voluntario_nome, voluntario_telefone, voluntario_email, voluntario_cep, voluntario_endereco, voluntario_senha) VALUES (?, ?, ?, ?, ?, ?)");
$stmt_insert->bind_param("ssssss", $nome, $telefone, $email, $cep, $endereco, $senha_hash);

if ($stmt_insert->execute()) {
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Cadastro realizado com sucesso! Faça seu login."));
} else {
    header("Location: /kindact/public/index.php?page=form_cadastro_voluntario&message=" . urlencode("Erro ao realizar o cadastro."));
}
exit();
?>