<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'voluntario') {
    header("Location: /kindact/main/login_voluntario.html");
    exit();
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voluntario_id = $_SESSION['user_id'];
    $ong_id = $_POST['ong_id'] ?? '';

    if (empty($ong_id)) {
        die("ID da ONG não fornecido.");
    }

    $stmt = $conn->prepare("INSERT INTO tb_candidatura (fk_voluntario_id, fk_ong_id, status) VALUES (?, ?, 'pendente')");
    $stmt->bind_param("ii", $voluntario_id, $ong_id);

    if ($stmt->execute()) {
        echo "Candidatura enviada com sucesso!";
        header("Location: /kindact/main/usuario.html");
        exit();
    } else {
        echo "Erro ao enviar candidatura.";
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Método não permitido. Use POST.";
}
$conn->close();
?>