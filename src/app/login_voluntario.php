<?php
require_once __DIR__ . '/../core/security.php';
require_once __DIR__ . '/../core/db_connect.php';
secure_session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT voluntario_id, voluntario_senha FROM tb_voluntario WHERE voluntario_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $voluntario = $result->fetch_assoc();
        if (password_verify($senha, $voluntario['voluntario_senha'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $voluntario['voluntario_id'];
            $_SESSION['user_type'] = 'voluntario';
            $_SESSION['initiated'] = true;
            header("Location: /kindact/public/index.php?page=voluntario_dashboard");
            exit();
        }
    }
    header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Email ou senha incorretos."));
    exit();
}
?>