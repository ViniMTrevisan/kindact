<?php
// Arquivo: login_admin.php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/main/login.html?message=" . urlencode("Email e senha são obrigatórios."));
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT admin_id, admin_senha FROM tb_admin WHERE admin_email = ?");
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta.");
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($senha, $admin['admin_senha'])) {
                $_SESSION['user_id'] = $admin['admin_id'];
                $_SESSION['user_type'] = 'admin';
                // Redireciona para o novo dashboard do admin
                header("Location: /kindact/main/admin_dashboard.php");
                exit();
            } else {
                header("Location: /kindact/main/login.html?message=" . urlencode("Senha incorreta."));
                exit();
            }
        } else {
            header("Location: /kindact/main/login.html?message=" . urlencode("Email não encontrado."));
            exit();
        }

        $stmt->close();
    } catch (Exception $e) {
        header("Location: /kindact/main/login.html?message=" . urlencode("Erro ao processar o login: " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: /kindact/main/login.html?message=" . urlencode("Método não permitido. Use POST."));
    exit();
}
$conn->close();
?>