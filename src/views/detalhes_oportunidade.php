<?php
// /src/views/detalhes_oportunidade.php
require_auth('voluntario');
$page_title = "Detalhes da Oportunidade";

$evento_id = filter_input(INPUT_GET, 'evento_id', FILTER_VALIDATE_INT);
if (!$evento_id) {
    header("Location: /kindact/public/index.php?page=voluntario_dashboard&message=" . urlencode("Oportunidade inválida."));
    exit();
}

$stmt = $conn->prepare("SELECT ev.*, ong.ong_nome FROM tb_evento ev JOIN tb_ong ong ON ev.fk_ong_id = ong.ong_id WHERE ev.evento_id = ?");
$stmt->bind_param("i", $evento_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: /kindact/public/index.php?page=voluntario_dashboard&message=" . urlencode("Oportunidade não encontrada."));
    exit();
}
$evento = $result->fetch_assoc();
$page_title = e($evento['evento_titulo']);
?>
<a href="/kindact/public/index.php?page=voluntario_dashboard" class="back-link">< Voltar</a>
<h2><?php echo e($evento['evento_titulo']); ?></h2>
<div class="opportunity-details">
    <p><strong>ONG:</strong> <?php echo e($evento['ong_nome']); ?></p>
    <p><strong>Data de Início:</strong> <?php echo e(date('d/m/Y', strtotime($evento['evento_data_inicio']))); ?></p>
    <p><strong>Descrição Completa:</strong></p>
    <p><?php echo nl2br(e($evento['evento_descricao'])); ?></p>
</div>

<form action="/kindact/public/index.php" method="post" style="margin-top: 20px;">
    <input type="hidden" name="action" value="envio">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="hidden" name="evento_id" value="<?php echo e($evento['evento_id']); ?>">
    <button type="submit" class="btn btn-primary">Confirmar Candidatura para esta Vaga</button>
</form>