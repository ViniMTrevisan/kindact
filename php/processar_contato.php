<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ong') {
    header("Location: /kindact/main/login_ong.html");
    exit();
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voluntario_id = $_POST['voluntario_id'] ?? '';
    $ong_id = $_SESSION['user_id'];

    if (empty($voluntario_id)) {
        die("ID do voluntário não fornecido.");
    }

    $stmt = $conn->prepare("UPDATE tb_candidatura SET status = 'contactado' WHERE fk_voluntario_id = ? AND fk_ong_id = ?");
    $stmt->bind_param("ii", $voluntario_id, $ong_id);

    if ($stmt->execute()) {
        header("Location: /kindact/main/contato_confirmado.html");
        exit();
    } else {
        echo "Erro ao processar contato.";
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Método não permitido. Use POST.";
}
$conn->close();
?>