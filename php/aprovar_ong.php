<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /kindact/main/login_admin.html");
    exit();
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ong_id = $_POST['ong_id'] ?? '';

    if (empty($ong_id)) {
        die("ID da ONG não fornecido.");
    }

    $stmt = $conn->prepare("UPDATE tb_ong SET aprovado = 1 WHERE ong_id = ?");
    $stmt->bind_param("i", $ong_id);

    if ($stmt->execute()) {
        header("Location: /kindact/main/aprovacao_admin.php");
        exit();
    } else {
        echo "Erro ao aprovar ONG.";
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Método não permitido. Use POST.";
}
$conn->close();
?>