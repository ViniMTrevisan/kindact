<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=form_cadastro_voluntario&message=" . urlencode("Erro de segurança."));
    exit();
}

require_once __DIR__ . '/../core/db_connect.php';

// ... (lógica de validação de dados como na resposta anterior) ...

$nome = trim($_POST['nome']);
$senha_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
// ... etc

$stmt = $conn->prepare("INSERT INTO tb_voluntario (voluntario_nome, voluntario_telefone, voluntario_email, voluntario_cep, voluntario_endereco, voluntario_senha) VALUES (?, ?, ?, ?, ?, ?)");
// ... bind_param e execute ...

if ($stmt->execute()) {
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Cadastro realizado com sucesso!"));
} else {
    header("Location: /kindact/public/index.php?page=form_cadastro_voluntario&message=" . urlencode("Erro no cadastro."));
}
exit();
?>