<?php
include 'security.php';
secure_session_start();

// 1. Validação do Método e do Token CSRF
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    // Redireciona para o formulário com uma mensagem de erro genérica de segurança
    header("Location: /kindact/main/form_cadastro_admin.php?message=" . urlencode("Erro de segurança ao processar sua solicitação. Tente novamente."));
    exit();
}

include 'db_connect.php';

// 2. Validação dos Dados de Entrada
$email = trim($_POST['email'] ?? '');
$senha = $_POST['password'] ?? '';

if (empty($email) || empty($senha)) {
    header("Location: /kindact/main/form_cadastro_admin.php?message=" . urlencode("Email e senha são obrigatórios."));
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /kindact/main/form_cadastro_admin.php?message=" . urlencode("O formato do email é inválido."));
    exit();
}

// 3. Verifica se o email já existe no banco
$stmt = $conn->prepare("SELECT admin_id FROM tb_admin WHERE admin_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: /kindact/main/form_cadastro_admin.php?message=" . urlencode("Este email já está cadastrado."));
    exit();
}
$stmt->close();

// 4. Cria o Hash da Senha e Insere no Banco
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt_insert = $conn->prepare("INSERT INTO tb_admin (admin_email, admin_senha) VALUES (?, ?)");
$stmt_insert->bind_param("ss", $email, $senha_hash);

if ($stmt_insert->execute()) {
    // Sucesso: Redireciona para a página de login com mensagem de sucesso
    header("Location: /kindact/main/login.html?message=" . urlencode("Administrador cadastrado com sucesso! Faça o login."));
} else {
    // Falha: Redireciona de volta ao formulário com mensagem de erro
    header("Location: /kindact/main/form_cadastro_admin.php?message=" . urlencode("Ocorreu um erro ao tentar realizar o cadastro. Tente novamente."));
}

$stmt_insert->close();
$conn->close();
exit();
?>