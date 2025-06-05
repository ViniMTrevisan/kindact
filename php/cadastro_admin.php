<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/main/cadastro_admin.html?message=Email%20e%20senha%20são%20obrigatórios.");
        exit();
    }

    // Verificar se o email já existe
    $stmt = $conn->prepare("SELECT admin_id FROM tb_admin WHERE admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: /kindact/main/cadastro_admin.html?message=Email%20já%20cadastrado.");
        exit();
    }

    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir o novo administrador
    $stmt = $conn->prepare("INSERT INTO tb_admin (admin_email, senha) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $senha_hash);

    if ($stmt->execute()) {
        header("Location: /kindact/main/login_admin.html?message=Administrador%20cadastrado%20com%20sucesso!");
        exit();
    } else {
        header("Location: /kindact/main/cadastro_admin.html?message=Erro%20ao%20cadastrar%20administrador.");
        exit();
    }

    $stmt->close();
} else {
    header("Location: /kindact/main/cadastro_admin.html?message=Método%20não%20permitido.%20Use%20POST.");
    exit();
}
$conn->close();
?>