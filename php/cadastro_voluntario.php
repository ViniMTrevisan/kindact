<?php
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

    if (empty($nome) || empty($telefone) || empty($email) || empty($cep) || empty($endereco) || empty($senha)) {
        die("Todos os campos obrigatórios devem ser preenchidos.");
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO tb_voluntario (voluntario_nome, voluntario_telefone, voluntario_email, voluntario_cep, voluntario_endereco, voluntario_senha) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $telefone, $email, $cep, $endereco, $senha_hash);

    if ($stmt->execute()) {
        echo "Voluntário cadastrado com sucesso!";
        header("Location: /kindact/main/login_voluntario.html");
        exit();
    } else {
        echo "Erro ao cadastrar voluntário.";
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Método não permitido. Use POST.";
}
$conn->close();
?>