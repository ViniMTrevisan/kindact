<?php
include 'security.php';
secure_session_start();

// 1. Validação do Método e do Token CSRF
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/main/form_cadastro_ong.php?message=" . urlencode("Erro de segurança. Por favor, envie o formulário novamente."));
    exit();
}

include 'db_connect.php';

// 2. Coleta e Validação dos Dados de Entrada
$nome = trim($_POST['nome'] ?? '');
$cnpj = trim($_POST['cnpj'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$area_atuacao = trim($_POST['area_atuacao'] ?? '');
$senha = $_POST['password'] ?? '';

if (empty($nome) || empty($cnpj) || empty($telefone) || empty($email) || empty($cep) || empty($endereco) || empty($senha)) {
    header("Location: /kindact/main/form_cadastro_ong.php?message=" . urlencode("Todos os campos marcados com * são obrigatórios."));
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /kindact/main/form_cadastro_ong.php?message=" . urlencode("Formato de email inválido."));
    exit();
}

// Limpa e valida o CNPJ (deve ter 14 dígitos)
$cnpj_clean = preg_replace('/[^0-9]/', '', $cnpj);
if (strlen($cnpj_clean) !== 14) {
    header("Location: /kindact/main/form_cadastro_ong.php?message=" . urlencode("CNPJ inválido. Deve conter 14 dígitos."));
    exit();
}

// Limpa e valida o Telefone (deve ter 10 ou 11 dígitos)
$telefone_clean = preg_replace('/[^0-9]/', '', $telefone);
if (!in_array(strlen($telefone_clean), [10, 11])) {
    header("Location: /kindact/main/form_cadastro_ong.php?message=" . urlencode("Telefone inválido. Inclua o DDD."));
    exit();
}

// 3. Verifica se o email ou CNPJ já existem
$stmt = $conn->prepare("SELECT ong_id FROM tb_ong WHERE ong_email = ? OR ong_cnpj = ?");
$stmt->bind_param("ss", $email, $cnpj_clean);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: /kindact/main/form_cadastro_ong.php?message=" . urlencode("Email ou CNPJ já cadastrado em nosso sistema."));
    exit();
}
$stmt->close();

// 4. Cria o Hash da Senha e Insere no Banco
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt_insert = $conn->prepare("INSERT INTO tb_ong (ong_nome, ong_cnpj, ong_telefone, ong_email, ong_cep, ong_endereco, ong_area_atuacao, ong_senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_insert->bind_param("ssssssss", $nome, $cnpj_clean, $telefone_clean, $email, $cep, $endereco, $area_atuacao, $senha_hash);

if ($stmt_insert->execute()) {
    header("Location: /kindact/main/login.html?message=" . urlencode("ONG cadastrada com sucesso! Aguarde a aprovação do administrador."));
} else {
    header("Location: /kindact/main/form_cadastro_ong.php?message=" . urlencode("Erro ao realizar o cadastro. Tente novamente."));
}

$stmt_insert->close();
$conn->close();
exit();
?>