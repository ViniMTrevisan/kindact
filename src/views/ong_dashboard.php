<?php
// /src/views/ong_dashboard.php
require_auth('ong');
$page_title = "Dashboard da ONG";
$ong_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT evento_id, evento_titulo, evento_data_inicio FROM tb_evento WHERE fk_ong_id = ? ORDER BY evento_data_inicio DESC");
$stmt->bind_param("i", $ong_id);
$stmt->execute();
$eventos_result = $stmt->get_result();
?>

<h2>Gerenciar Oportunidades</h2>
<div id="message-container"></div>

<section id="publicar-oportunidade" class="form-container">
    <h3>Publicar Nova Oportunidade</h3>
    <form action="/kindact/public/index.php" method="post">
        <input type="hidden" name="action" value="gerenciar_oportunidade">
        <input type="hidden" name="sub_action" value="criar">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

        <div class="form-group"><label for="titulo">Título da Oportunidade:</label><input type="text" id="titulo" name="titulo" required></div>
        <div class="form-group"><label for="descricao">Descrição Completa:</label><textarea id="descricao" name="descricao" rows="4" required></textarea></div>
        <div class="form-group"><label for="data_inicio">Data de Início:</label><input type="date" id="data_inicio" name="data_inicio" required></div>
        <button type="submit" class="btn btn-primary">Publicar Oportunidade</button>
    </form>
</section>

<section id="oportunidades-publicadas" style="margin-top: 40px;">
    <h3>Minhas Oportunidades Publicadas</h3>
    <?php if ($eventos_result->num_rows > 0): ?>
        <ul class="event-list">
            <?php while ($evento = $eventos_result->fetch_assoc()): ?>
                <li class="event-item">
                    <h4><?php echo e($evento['evento_titulo']); ?></h4>
                    <p>Data: <?php echo e(date('d/m/Y', strtotime($evento['evento_data_inicio']))); ?></p>
                    <div class="event-actions">
                        <a href="/kindact/public/index.php?page=gerenciar_candidatos&evento_id=<?php echo e($evento['evento_id']); ?>" class="btn btn-primary">Ver Candidatos</a>
                        <a href="/kindact/public/index.php?page=gerenciar_oportunidade&evento_id=<?php echo e($evento['evento_id']); ?>" class="btn btn-secondary">Editar / Excluir</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Você ainda não publicou nenhuma oportunidade.</p>
    <?php endif; ?>
</section>