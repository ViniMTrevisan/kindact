<?php
require_auth('voluntario');
$page_title = "Minhas Candidaturas";
$voluntario_id = $_SESSION['user_id'];

$sql = "SELECT c.status, e.evento_titulo, o.ong_nome
        FROM tb_candidatura c
        JOIN tb_evento e ON c.fk_evento_id = e.evento_id
        JOIN tb_ong o ON e.fk_ong_id = o.ong_id
        WHERE c.fk_voluntario_id = ? ORDER BY c.candidatura_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $voluntario_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<h2><?php echo e($page_title); ?></h2>
<div id="message-container"></div>

<?php if ($result && $result->num_rows > 0): ?>
    <ul class="candidatura-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="candidatura-item">
                <h4><?php echo e($row['evento_titulo']); ?></h4>
                <p><strong>ONG:</strong> <?php echo e($row['ong_nome']); ?></p>
                <p><strong>Status:</strong> <span class="status-badge status-<?php echo e($row['status']); ?>"><?php echo e($row['status']); ?></span></p>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>Você ainda não se candidatou a nenhuma oportunidade.</p>
<?php endif; ?>