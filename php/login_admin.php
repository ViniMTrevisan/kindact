<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        die("Email e senha são obrigatórios.");
    }

    $stmt = $conn->prepare("SELECT admin_id, senha FROM tb_admin WHERE admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($senha, $admin['senha'])) {
            $_SESSION['user_id'] = $admin['admin_id'];
            $_SESSION['user_type'] = 'admin';
            header("Location: /kindact/main/admin_tela.html");
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Email não encontrado.";
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Método não permitido. Use POST.";
}
$conn->close();
?>