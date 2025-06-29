\<?php
// /src/app/gerenciar_oportunidade.php

// O roteador já iniciou a sessão e a conexão com o banco.
// Aqui, apenas garantimos a autorização e processamos a lógica.
require_auth('ong');

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Erro de segurança."));
    exit();
}

$sub_action = $_POST['sub_action'] ?? '';
$ong_id = $_SESSION['user_id'];

switch ($sub_action) {
    case 'criar':
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $data_inicio = $_POST['data_inicio'] ?? '';
        
        if (empty($titulo) || empty($descricao) || empty($data_inicio)) {
            header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Campos obrigatórios não preenchidos."));
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO tb_evento (evento_titulo, evento_descricao, evento_data_inicio, fk_ong_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $titulo, $descricao, $data_inicio, $ong_id);

        if ($stmt->execute()) {
            header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Oportunidade publicada com sucesso!"));
        } else {
            header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Erro ao publicar."));
        }
        break;

    case 'editar':
        $evento_id = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $data_inicio = $_POST['data_inicio'] ?? '';

        if (!$evento_id || empty($titulo) || empty($descricao) || empty($data_inicio)) {
            header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Dados inválidos para edição."));
            exit();
        }

        $stmt = $conn->prepare("UPDATE tb_evento SET evento_titulo = ?, evento_descricao = ?, evento_data_inicio = ? WHERE evento_id = ? AND fk_ong_id = ?");
        $stmt->bind_param("sssii", $titulo, $descricao, $data_inicio, $evento_id, $ong_id);

        if ($stmt->execute()) {
            header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Oportunidade atualizada com sucesso!"));
        } else {
            header("Location: /kindact/public/index.php?page=gerenciar_oportunidade&evento_id={$evento_id}&message=" . urlencode("Erro ao atualizar."));
        }
        break;

    case 'excluir':
        $evento_id = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);
        if (!$evento_id) {
            header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("ID inválido."));
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM tb_evento WHERE evento_id = ? AND fk_ong_id = ?");
        $stmt->bind_param("ii", $evento_id, $ong_id);

        if ($stmt->execute()) {
            header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Oportunidade removida."));
        } else {
            header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Erro ao remover."));
        }
        break;

    default:
        header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Ação desconhecida."));
        break;
}
exit();
?>