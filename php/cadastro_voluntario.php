<?php
include 'security.php';
secure_session_start();

// 1. Validação do Método e do Token CSRF
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/main/form_cadastro_voluntario.php?message=" . urlencode("Erro de segurança. Por favor, envie o formulário novamente."));
    exit();
}

include 'db_connect.php';

// 2. Coleta e Validação dos Dados de Entrada
$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$senha = $_POST['password'] ?? '';

if (empty($nome) || empty($telefone) || empty($email) || empty($cep) || empty($endereco) || empty($senha)) {
    header("Location: /kindact/main/form_cadastro_voluntario.php?message=" . urlencode("Todos os campos são obrigatórios."));
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /kindact/main/form_cadastro_voluntario.php?message=" . urlencode("Formato de email inválido."));
    exit();
}

// Limpa e valida o Telefone (deve ter 10 ou 11 dígitos)
$telefone_clean = preg_replace('/[^0-9]/', '', $telefone);
if (!in_array(strlen($telefone_clean), [10, 11])) {
    header("Location: /kindact/main/form_cadastro_voluntario.php?message=" . urlencode("Telefone inválido. Inclua o DDD."));
    exit();
}

// 3. Verifica se o email já existe
$stmt = $conn->prepare("SELECT voluntario_id FROM tb_voluntario WHERE voluntario_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: /kindact/main/form_cadastro_voluntario.php?message=" . urlencode("Este email já está cadastrado."));
    exit();
}
$stmt->close();

// 4. Cria o Hash da Senha e Insere no Banco
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt_insert = $conn->prepare("INSERT INTO tb_voluntario (voluntario_nome, voluntario_telefone, voluntario_email, voluntario_cep, voluntario_endereco, voluntario_senha) VALUES (?, ?, ?, ?, ?, ?)");
$stmt_insert->bind_param("ssssss", $nome, $telefone_clean, $email, $cep, $endereco, $senha_hash);

if ($stmt_insert->execute()) {
    header("Location: /kindact/main/login.html?message=" . urlencode("Cadastro realizado com sucesso! Faça seu login."));
} else {
    header("Location: /kindact/main/form_cadastro_voluntario.php?message=" . urlencode("Erro ao realizar o cadastro. Tente novamente."));
}

$stmt_insert->close();
$conn->close();
exit();
?>