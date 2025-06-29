<?php
require_once __DIR__ . '/../core/db_connect.php';
header('Content-Type: application/json');

// O ideal é ter alguma autenticação aqui
// require_once __DIR__ . '/../core/security.php';
// secure_session_start();
// require_auth('admin'); 

$result = $conn->query("SELECT voluntario_id, voluntario_nome, voluntario_email FROM tb_voluntario");
$voluntarios = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($voluntarios);
exit();
?>