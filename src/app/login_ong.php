<?php
// Arquivo: login_ong.php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/main/login.html?message=" . urlencode("Email e senha são obrigatórios."));
        exit();
    }

    $stmt = $conn->prepare("SELECT ong_id, ong_senha, aprovado FROM tb_ong WHERE ong_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $ong = $result->fetch_assoc();
        if ($ong['aprovado'] == 0) {
            header("Location: /kindact/main/login.html?message=" . urlencode("Sua ONG ainda não foi aprovada pelo administrador."));
            exit();
        }
        if (password_verify($senha, $ong['ong_senha'])) {
            $_SESSION['user_id'] = $ong['ong_id'];
            $_SESSION['user_type'] = 'ong';
            // Redireciona para o novo dashboard da ONG
            header("Location: /kindact/main/ong_dashboard.php");
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