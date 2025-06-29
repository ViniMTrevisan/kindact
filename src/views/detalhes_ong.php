<?php
require_auth('voluntario');
$page_title = "Detalhes da ONG";

$ong_id = filter_input(INPUT_GET, 'ong_id', FILTER_VALIDATE_INT);
if (!$ong_id) {
    header("Location: /kindact/public/index.php?page=voluntario_dashboard&message=" . urlencode("ONG inválida."));
    exit();
}

$stmt_ong = $conn->prepare("SELECT * FROM tb_ong WHERE ong_id = ? AND aprovado = 1");
$stmt_ong->bind_param("i", $ong_id);
$stmt_ong->execute();
$ong_result = $stmt_ong->get_result();
if ($ong_result->num_rows === 0) {
    header("Location: /kindact/public/index.php?page=voluntario_dashboard&message=" . urlencode("ONG não encontrada."));
    exit();
}
$ong = $ong_result->fetch_assoc();
$page_title = e($ong['ong_nome']);

$eventos_result = $conn->query("SELECT * FROM tb_evento WHERE fk_ong_id = $ong_id ORDER BY evento_data_inicio DESC");
?>
<a href="/kindact/public/index.php?page=voluntario_dashboard" class="back-link">< Voltar para Oportunidades</a>
<h2><?php echo e($ong['ong_nome']); ?></h2>
<div class="profile-details">
    <p><strong>Área de Atuação:</strong> <?php echo e($ong['ong_area_atuacao']); ?></p>
    <p><strong>Email para Contato:</strong> <?php echo e($ong['ong_email']); ?></p>
    <p><strong>Sobre a ONG:</strong> <?php echo nl2br(e($ong['ong_descricao'] ?: 'Nenhuma descrição fornecida.')); ?></p>
</div>

<section id="oportunidades-ong" style="margin-top: 40px;">
    <h3>Oportunidades Abertas nesta ONG</h3>
    <?php if ($eventos_result && $eventos_result->num_rows > 0): ?>
        <ul class="event-list">
            <?php while ($evento = $eventos_result->fetch_assoc()): ?>
                <li class="event-item">
                    <h4><?php echo e($evento['evento_titulo']); ?></h4>
                    <p><?php echo e(substr($evento['evento_descricao'], 0, 150)); ?>...</p>
                    <a href="/kindact/public/index.php?page=detalhes_oportunidade&evento_id=<?php echo e($evento['evento_id']); ?>" class="btn btn-primary">Ver Detalhes e Candidatar-se</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Esta ONG não possui oportunidades publicadas no momento.</p>
    <?php endif; ?>
</section>