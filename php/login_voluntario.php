<?php
// Arquivo: login_voluntario.php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/main/login.html?message=" . urlencode("Email e senha são obrigatórios."));
        exit();
    }

    $stmt = $conn->prepare("SELECT voluntario_id, voluntario_senha FROM tb_voluntario WHERE voluntario_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $voluntario = $result->fetch_assoc();
        if (password_verify($senha, $voluntario['voluntario_senha'])) {
            $_SESSION['user_id'] = $voluntario['voluntario_id'];
            $_SESSION['user_type'] = 'voluntario';
            // Redireciona para o novo dashboard do voluntário
            header("Location: /kindact/main/voluntario_dashboard.php");
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
} else {
    header("Location: /kindact/main/login.html?message=" . urlencode("Método não permitido. Use POST."));
    exit();
}
$conn->close();
?>