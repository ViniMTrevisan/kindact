<?php
require_auth('ong'); 
$page_title = "Gerenciar Oportunidade";
$ong_id = $_SESSION['user_id'];

$evento_id = filter_input(INPUT_GET, 'evento_id', FILTER_VALIDATE_INT);
if (!$evento_id) {
    header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Oportunidade inválida."));
    exit();
}

$stmt = $conn->prepare("SELECT * FROM tb_evento WHERE evento_id = ? AND fk_ong_id = ?");
$stmt->bind_param("ii", $evento_id, $ong_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: /kindact/public/index.php?page=ong_dashboard&message=" . urlencode("Acesso negado a esta oportunidade."));
    exit();
}
$evento = $result->fetch_assoc();
?>

<a href="/kindact/public/index.php?page=ong_dashboard" class="back-link">< Voltar ao Dashboard</a>
<h2>Editar Oportunidade: <?php echo e($evento['evento_titulo']); ?></h2>
<div id="message-container"></div>

<section id="editar-oportunidade" class="form-container">
    <form action="/kindact/public/index.php" method="post">
        <input type="hidden" name="action" value="gerenciar_oportunidade">
        <input type="hidden" name="sub_action" value="editar">
        <input type="hidden" name="evento_id" value="<?php echo e($evento['evento_id']); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo e($evento['evento_titulo']); ?>" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4" required><?php echo e($evento['evento_descricao']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="data_inicio">Data de Início:</label>
            <input type="date" id="data_inicio" name="data_inicio" value="<?php echo e($evento['evento_data_inicio']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</section>

<section id="excluir-oportunidade" style="margin-top: 40px;">
    <h3>Excluir Oportunidade</h3>
    <p>Esta ação não pode ser desfeita.</p>
    <form action="/kindact/public/index.php" method="post">
        <input type="hidden" name="action" value="gerenciar_oportunidade">
        <input type="hidden" name="sub_action" value="excluir">
        <input type="hidden" name="evento_id" value="<?php echo e($evento['evento_id']); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <button type="submit" class="btn btn-danger remover">Excluir Permanentemente</button>
    </form>
</section>