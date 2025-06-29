<?php
require_auth('ong');
$page_title = "Gerenciar Candidatos";
$ong_id = $_SESSION['user_id'];
$evento_id = filter_input(INPUT_GET, 'evento_id', FILTER_VALIDATE_INT);

if (!$evento_id) { header("Location: /kindact/public/index.php?page=ong_dashboard"); exit(); }

$stmt_evento = $conn->prepare("SELECT evento_titulo FROM tb_evento WHERE evento_id = ? AND fk_ong_id = ?");
$stmt_evento->bind_param("ii", $evento_id, $ong_id);
$stmt_evento->execute();
$result_evento = $stmt_evento->get_result();
if ($result_evento->num_rows === 0) { header("Location: /kindact/public/index.php?page=ong_dashboard"); exit(); }
$evento = $result_evento->fetch_assoc();
$page_title = "Candidatos para: " . e($evento['evento_titulo']);

$stmt_candidatos = $conn->prepare("SELECT v.voluntario_id, v.voluntario_nome, v.voluntario_email, c.status FROM tb_candidatura c JOIN tb_voluntario v ON c.fk_voluntario_id = v.voluntario_id WHERE c.fk_evento_id = ?");
$stmt_candidatos->bind_param("i", $evento_id);
$stmt_candidatos->execute();
$result_candidatos = $stmt_candidatos->get_result();
?>
<a href="/kindact/public/index.php?page=ong_dashboard" class="back-link">< Voltar ao Dashboard</a>
<h2><?php echo e($page_title); ?></h2>
<div id="message-container"></div>
<?php if ($result_candidatos && $result_candidatos->num_rows > 0): ?>
    <ul class="voluntario-list">
        <?php while ($row = $result_candidatos->fetch_assoc()): ?>
            <li class="voluntario-item">
                <h4><?php echo e($row['voluntario_nome']); ?></h4>
                <p>Email: <?php echo e($row['voluntario_email']); ?></p>
                <p>Status: <span class="status-badge status-<?php echo e($row['status']); ?>"><?php echo e($row['status']); ?></span></p>
                <?php if ($row['status'] == 'pendente'): ?>
                    <form action="/kindact/src/app/processar_contato.php" method="post" style="margin-top: 10px;">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="voluntario_id" value="<?php echo e($row['voluntario_id']); ?>">
                        <input type="hidden" name="evento_id" value="<?php echo e($evento_id); ?>">
                        <button type="submit" class="btn btn-primary">Marcar como Contactado</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>Nenhum voluntário se candidatou para esta oportunidade ainda.</p>
<?php endif; ?>