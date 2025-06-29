<?php
require_auth('voluntario');
$page_title = "Oportunidades de Voluntariado";

$area_atuacao_filter = $_GET['area_atuacao'] ?? '';
$sql = "SELECT e.evento_id, e.evento_titulo, o.ong_id, o.ong_nome, o.ong_area_atuacao
        FROM tb_evento e JOIN tb_ong o ON e.fk_ong_id = o.ong_id
        WHERE o.aprovado = 1";
$params = [];
$types = '';
if (!empty($area_atuacao_filter)) {
    $sql .= " AND o.ong_area_atuacao = ?";
    $params[] = $area_atuacao_filter;
    $types .= 's';
}
$sql .= " ORDER BY e.evento_data_inicio DESC";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$areas_result = $conn->query("SELECT DISTINCT ong_area_atuacao FROM tb_ong WHERE aprovado = 1 AND ong_area_atuacao IS NOT NULL ORDER BY ong_area_atuacao");
?>
<h2>Encontre sua Oportunidade</h2>
<div id="message-container"></div>
<form class="filter-form" method="get" action="/kindact/public/index.php">
    <input type="hidden" name="page" value="voluntario_dashboard">
    <div class="form-group">
        <label for="area_atuacao">Filtrar por Área de Atuação:</label>
        <select id="area_atuacao" name="area_atuacao" onchange="this.form.submit()">
            <option value="">Todas as Áreas</option>
            <?php while($area = $areas_result->fetch_assoc()): ?>
                <option value="<?php echo e($area['ong_area_atuacao']); ?>" <?php if($area_atuacao_filter == $area['ong_area_atuacao']) echo 'selected'; ?>>
                    <?php echo e($area['ong_area_atuacao']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
</form>

<div class="opportunity-list">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="ong-item">
                <h3><?php echo e($row['evento_titulo']); ?></h3>
                <p><strong>ONG:</strong> <a href="/kindact/public/index.php?page=detalhes_ong&ong_id=<?php echo e($row['ong_id']); ?>"><?php echo e($row['ong_nome']); ?></a></p>
                <p><strong>Área:</strong> <?php echo e($row['ong_area_atuacao']); ?></p>
                <a href="/kindact/public/index.php?page=detalhes_oportunidade&evento_id=<?php echo e($row['evento_id']); ?>" class="btn btn-primary">Ver Detalhes e Candidatar-se</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Nenhuma oportunidade encontrada com os filtros selecionados.</p>
    <?php endif; ?>
</div>