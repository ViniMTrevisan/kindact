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

    $stmt = $conn->prepare("SELECT ong_id, ong_senha, aprovado FROM tb_ong WHERE ong_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $ong = $result->fetch_assoc();
        if ($ong['aprovado'] == 0) {
            header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Sua ONG ainda não foi aprovada."));
            exit();
        }
        if (password_verify($senha, $ong['ong_senha'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $ong['ong_id'];
            $_SESSION['user_type'] = 'ong';
            $_SESSION['initiated'] = true;
            header("Location: /kindact/public/index.php?page=ong_dashboard");
            exit();
        }
    }
    
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Email ou senha incorretos."));
    exit();
}
header("Location: /kindact/public/");
exit();
?>