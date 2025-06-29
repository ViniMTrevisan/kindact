<?php
require_once __DIR__ . '/../core/security.php';
require_once __DIR__ . '/../core/db_connect.php';
secure_session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Email e senha são obrigatórios."));
        exit();
    }

    $stmt = $conn->prepare("SELECT admin_id, admin_senha FROM tb_admin WHERE admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($senha, $admin['admin_senha'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $admin['admin_id'];
            $_SESSION['user_type'] = 'admin';
            $_SESSION['initiated'] = true;
            header("Location: /kindact/public/index.php?page=admin_dashboard");
            exit();
        }
    }
    
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Credenciais administrativas incorretas."));
    exit();
}
header("Location: /kindact/public/");
exit();
?>