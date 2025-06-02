<?php
include 'db_connect.php';

$result = $conn->query("SELECT voluntario_id, voluntario_nome, voluntario_cidade FROM tb_voluntario");
$voluntarios = [];

while ($row = $result->fetch_assoc()) {
    $voluntarios[] = $row;
}

header('Content-Type: application/json');
echo json_encode($voluntarios);

$conn->close();
?>