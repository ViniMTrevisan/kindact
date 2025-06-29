<?php
require_once __DIR__ . '/../core/db_connect.php';
header('Content-Type: application/json');

// O ideal é ter alguma autenticação aqui se os dados forem sensíveis
// require_once __DIR__ . '/../core/security.php';
// secure_session_start();
// require_auth('admin'); // Exemplo: apenas admin pode ver a lista completa

$result = $conn->query("SELECT ong_id, ong_nome, ong_area_atuacao FROM tb_ong WHERE aprovado = 1");
$ongs = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($ongs);
exit();
?>