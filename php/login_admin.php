<?php
// Arquivo: /kindact/php/login_admin.php
include 'security.php';
secure_session_start(); // Inicia a sessão de forma segura

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/main/login.html?message=" . urlencode("Email e senha são obrigatórios."));
        exit();
    }

    $stmt = $conn->prepare("SELECT admin_id, admin_senha FROM tb_admin WHERE admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($senha, $admin['admin_senha'])) {
            // Login bem-sucedido!
            session_regenerate_id(true); // Regenera o ID da sessão após o login
            $_SESSION['user_id'] = $admin['admin_id'];
            $_SESSION['user_type'] = 'admin';
            $_SESSION['initiated'] = true; // Confirma a inicialização segura
            
            header("Location: /kindact/main/admin_dashboard.php");
            exit();
        }
    }
    
    // Se chegou aqui, o login falhou
    header("Location: /kindact/main/login.html?message=" . urlencode("Email ou senha incorretos."));
    exit();
}
?>