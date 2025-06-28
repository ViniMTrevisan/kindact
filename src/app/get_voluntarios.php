<?php
include 'db_connect.php';

$sql = "SELECT voluntario_id, voluntario_nome, voluntario_email FROM tb_voluntario";
$result = $conn->query($sql);

$voluntarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $voluntarios[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($voluntarios);

$conn->close();
?>