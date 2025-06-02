<?php
include 'db_connect.php';

$result = $conn->query("SELECT ong_id, ong_nome, ong_area_atuacao, aprovado FROM tb_ong");
$ongs = [];

while ($row = $result->fetch_assoc()) {
    $ongs[] = $row;
}

header('Content-Type: application/json');
echo json_encode($ongs);

$conn->close();
?>