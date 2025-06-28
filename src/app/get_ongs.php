<?php
include 'db_connect.php';

$sql = "SELECT ong_id, ong_nome, ong_area_atuacao FROM tb_ong WHERE aprovado = 1";
$result = $conn->query($sql);

$ongs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ongs[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($ongs);

$conn->close();
?>