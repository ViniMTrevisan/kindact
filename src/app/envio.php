<?php
// /src/app/envio.php (VERSÃO FINAL E CORRIGIDA)

// O roteador já cuidou da segurança e da conexão com o banco.
require_auth('voluntario');

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=voluntario_dashboard&message=" . urlencode("Erro de segurança. Por favor, tente novamente."));
    exit();
}

$voluntario_id = $_SESSION['user_id'];
$evento_id = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);

if (!$evento_id) {
    header("Location: /kindact/public/index.php?page=voluntario_dashboard&message=" . urlencode("Oportunidade inválida."));
    exit();
}

// PASSO 1 (NOVO): Descobrir a qual ONG este evento pertence.
$stmt_get_ong = $conn->prepare("SELECT fk_ong_id FROM tb_evento WHERE evento_id = ?");
$stmt_get_ong->bind_param("i", $evento_id);
$stmt_get_ong->execute();
$result_ong = $stmt_get_ong->get_result();
if ($result_ong->num_rows === 0) {
    header("Location: /kindact/public/index.php?page=voluntario_dashboard&message=" . urlencode("Oportunidade não encontrada."));
    exit();
}
$ong_id_do_evento = $result_ong->fetch_assoc()['fk_ong_id'];
$stmt_get_ong->close();


// PASSO 2: Verificar se o usuário já se candidatou (código que já tínhamos)
$stmt_check = $conn->prepare("SELECT candidatura_id FROM tb_candidatura WHERE fk_voluntario_id = ? AND fk_evento_id = ?");
$stmt_check->bind_param("ii", $voluntario_id, $evento_id);
$stmt_check->execute();
if ($stmt_check->get_result()->num_rows > 0) {
    header("Location: /kindact/public/index.php?page=minhas_candidaturas&message=" . urlencode("Você já se candidatou para esta oportunidade."));
    exit();
}
$stmt_check->close();


// PASSO 3 (CORRIGIDO): Inserir a nova candidatura com TODAS as informações necessárias.
$stmt_insert = $conn->prepare("INSERT INTO tb_candidatura (fk_voluntario_id, fk_ong_id, fk_evento_id, status) VALUES (?, ?, ?, 'pendente')");
// O bind agora tem 3 inteiros: id do voluntário, id da ong, id do evento
$stmt_insert->bind_param("iii", $voluntario_id, $ong_id_do_evento, $evento_id);

if ($stmt_insert->execute()) {
    header("Location: /kindact/public/index.php?page=minhas_candidaturas&message=" . urlencode("Candidatura enviada com sucesso!"));
} else {
    header("Location: /kindact/public/index.php?page=voluntario_dashboard&message=" . urlencode("Erro inesperado ao registrar candidatura."));
}
exit();
?>