<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        die("Email e senha são obrigatórios.");
    }

    $stmt = $conn->prepare("SELECT ong_id, senha, aprovado FROM tb_ong WHERE ong_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $ong = $result->fetch_assoc();
        if ($ong['aprovado'] == 0) {
            die("Sua ONG ainda não foi aprovada pelo administrador.");
        }
        if (password_verify($senha, $ong['senha'])) {
            $_SESSION['user_id'] = $ong['ong_id'];
            $_SESSION['user_type'] = 'ong';
            header("Location: /kindact/main/publicacao_ong.php");
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