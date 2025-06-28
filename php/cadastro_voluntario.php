<?php
// Arquivo: cadastro_voluntario.php
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $senha = $_POST['password'] ?? '';

    // --- Nova Validação de Entrada de Dados ---
    if (empty($nome) || empty($telefone) || empty($email) || empty($cep) || empty($endereco) || empty($senha)) {
        header("Location: /kindact/main/cadastro_voluntario.html?message=" . urlencode("Todos os campos obrigatórios devem ser preenchidos."));
        exit();
    }
    // Validação de formato para email e telefone
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /kindact/main/cadastro_voluntario.html?message=" . urlencode("Formato de email inválido."));
        exit();
    }
    $telefone_clean = preg_replace('/[^0-9]/', '', $telefone);
    if (!preg_match('/^\d{10,11}$/', $telefone_clean)) {
        header("Location: /kindact/main/cadastro_voluntario.html?message=" . urlencode("Formato de telefone inválido. Use DDD + número (10 ou 11 dígitos)."));
        exit();
    }
    // --- Fim da Validação ---

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO tb_voluntario (voluntario_nome, voluntario_telefone, voluntario_email, voluntario_cep, voluntario_endereco, voluntario_senha) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $telefone_clean, $email, $cep, $endereco, $senha_hash);

    if ($stmt->execute()) {
        header("Location: /kindact/main/login.html?message=" . urlencode("Voluntário cadastrado com sucesso!"));
        exit();
    } else {
        header("Location: /kindact/main/cadastro_voluntario.html?message=" . urlencode("Erro ao cadastrar voluntário."));
        exit();
    }

    $stmt->close();
} else {
    header("Location: /kindact/main/cadastro_voluntario.html?message=" . urlencode("Método não permitido. Use POST."));
    exit();
}
$conn->close();
?>