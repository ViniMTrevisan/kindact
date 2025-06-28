<?php
// Arquivo: cadastro_admin.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if (!$conn) {
    header("Location: /kindact/main/cadastro_admin.html?message=" . urlencode("Erro: Conexão com o banco de dados não estabelecida."));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/main/cadastro_admin.html?message=" . urlencode("Email e senha são obrigatórios."));
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT admin_id FROM tb_admin WHERE admin_email = ?");
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta de verificação: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta de verificação: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $stmt->close();
            header("Location: /kindact/main/cadastro_admin.html?message=" . urlencode("Email já cadastrado."));
            exit();
        }
        $stmt->close();

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        if (!$senha_hash) {
            throw new Exception("Erro ao gerar o hash da senha.");
        }

        $stmt = $conn->prepare("INSERT INTO tb_admin (admin_email, admin_senha) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta de inserção: " . $conn->error);
        }

        $stmt->bind_param("ss", $email, $senha_hash);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a inserção: " . $stmt->error);
        }

        $stmt->close();
        header("Location: /kindact/main/login.html?message=" . urlencode("Administrador cadastrado com sucesso! Faça login."));
        exit();
    } catch (Exception $e) {
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
        header("Location: /kindact/main/cadastro_admin.html?message=" . urlencode("Erro: " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: /kindact/main/cadastro_admin.html?message=" . urlencode("Método não permitido. Use POST."));
    exit();
}

$conn->close();
?>