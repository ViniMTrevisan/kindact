<?php
session_start();
include 'db_connect.php';

if ($conn->connect_error) {
    header("Location: /kindact/main/login_admin.html?message=Erro%20de%20conexão%20com%20o%20banco%20de%20dados.");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/main/login_admin.html?message=Email%20e%20senha%20são%20obrigatórios.");
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT admin_id, senha FROM tb_admin WHERE admin_email = ?");
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta.");
        }

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
                header("Location: /kindact/main/login_admin.html?message=Senha%20incorreta.");
                exit();
            }
        } else {
            header("Location: /kindact/main/login_admin.html?message=Email%20não%20encontrado.");
            exit();
        }

        $stmt->close();
    } catch (Exception $e) {
        header("Location: /kindact/main/login_admin.html?message=Erro%20ao%20processar%20o%20login:%20" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: /kindact/main/login_admin.html?message=Método%20não%20permitido.%20Use%20POST.");
    exit();
}
$conn->close();
?>