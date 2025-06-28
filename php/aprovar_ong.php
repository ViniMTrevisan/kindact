<?php
// Arquivo: aprovar_ong.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /kindact/main/login.html?message=" . urlencode("Acesso não autorizado."));
    exit();
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ong_id = $_POST['ong_id'] ?? '';

    if (empty($ong_id)) {
        header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("ID da ONG não fornecido."));
        exit();
    }

    $stmt = $conn->prepare("UPDATE tb_ong SET aprovado = 1 WHERE ong_id = ?");
    $stmt->bind_param("i", $ong_id);

    if ($stmt->execute()) {
        header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("ONG aprovada com sucesso."));
        exit();
    } else {
        header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("Erro ao aprovar ONG."));
        exit();
    }

    $stmt->close();
} else {
    header("Location: /kindact/main/admin_dashboard.php?message=" . urlencode("Método não permitido. Use POST."));
    exit();
}
$conn->close();
?>