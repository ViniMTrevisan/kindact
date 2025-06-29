<?php
require_once __DIR__ . '/../core/db_connect.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT ong_id, ong_nome, ong_area_atuacao FROM tb_ong WHERE aprovado = 1");
$ongs = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($ongs);
exit();
?>