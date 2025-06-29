<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=form_cadastro_ong&message=" . urlencode("Erro de segurança."));
    exit();
}

require_once __DIR__ . '/../core/db_connect.php';

$nome = trim($_POST['nome'] ?? '');
$cnpj = preg_replace('/[^0-9]/', '', $_POST['cnpj'] ?? '');
$telefone = preg_replace('/[^0-9]/', '', $_POST['telefone'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$cep = trim($_POST['cep'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$area_atuacao = trim($_POST['area_atuacao'] ?? '');
$senha = $_POST['password'] ?? '';

if (empty($nome) || empty($cnpj) || empty($telefone) || !$email || empty($cep) || empty($endereco) || empty($senha)) {
    header("Location: /kindact/public/index.php?page=form_cadastro_ong&message=" . urlencode("Todos os campos obrigatórios devem ser preenchidos corretamente."));
    exit();
}

$stmt_check = $conn->prepare("SELECT ong_id FROM tb_ong WHERE ong_email = ? OR ong_cnpj = ?");
$stmt_check->bind_param("ss", $email, $cnpj);
$stmt_check->execute();
if ($stmt_check->get_result()->num_rows > 0) {
    header("Location: /kindact/public/index.php?page=form_cadastro_ong&message=" . urlencode("Email ou CNPJ já cadastrado."));
    exit();
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt_insert = $conn->prepare("INSERT INTO tb_ong (ong_nome, ong_cnpj, ong_telefone, ong_email, ong_cep, ong_endereco, ong_area_atuacao, ong_senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_insert->bind_param("ssssssss", $nome, $cnpj, $telefone, $email, $cep, $endereco, $area_atuacao, $senha_hash);

if ($stmt_insert->execute()) {
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("ONG cadastrada! Aguarde a aprovação do administrador."));
} else {
    header("Location: /kindact/public/index.php?page=form_cadastro_ong&message=" . urlencode("Erro ao realizar o cadastro."));
}
exit();
?>