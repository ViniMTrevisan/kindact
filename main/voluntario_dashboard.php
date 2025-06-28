<?php
include '../php/security.php';
secure_session_start();
require_auth('voluntario');
include '../php/db_connect.php';

// Lógica de Filtro
$area_atuacao_filter = $_GET['area_atuacao'] ?? '';
$sql = "SELECT e.evento_id, e.evento_titulo, o.ong_id, o.ong_nome, o.ong_area_atuacao
        FROM tb_evento e
        JOIN tb_ong o ON e.fk_ong_id = o.ong_id
        WHERE o.aprovado = 1";
$params = [];
$types = '';

if (!empty($area_atuacao_filter)) {
    $sql .= " AND o.ong_area_atuacao = ?";
    $params[] = $area_atuacao_filter;
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Busca todas as áreas de atuação para o dropdown do filtro
$areas_result = $conn->query("SELECT DISTINCT ong_area_atuacao FROM tb_ong WHERE aprovado = 1 AND ong_area_atuacao IS NOT NULL ORDER BY ong_area_atuacao");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Área do Voluntário</title>
</head>
<body>
    <div class="container">
        <main>
            <h2>Oportunidades de Voluntariado</h2>
            <div id="message-container"></div>
            
            <form class="filter-form" method="get" action="">
                <div class="form-group">
                    <label for="area_atuacao">Filtrar por Área de Atuação:</label>
                    <select id="area_atuacao" name="area_atuacao">
                        <option value="">Todas as Áreas</option>
                        <?php while($area = $areas_result->fetch_assoc()): ?>
                            <option value="<?php echo e($area['ong_area_atuacao']); ?>" <?php if($area_atuacao_filter == $area['ong_area_atuacao']) echo 'selected'; ?>>
                                <?php echo e($area['ong_area_atuacao']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>

            <div class="opportunity-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="ong-item">
                            <h3><?php echo e($row['evento_titulo']); ?></h3>
                            <p><strong>ONG:</strong> <a href="/kindact/main/detalhes_ong.php?ong_id=<?php echo e($row['ong_id']); ?>"><?php echo e($row['ong_nome']); ?></a></p>
                            <p><strong>Área:</strong> <?php echo e($row['ong_area_atuacao']); ?></p>
                            <a href="/kindact/main/detalhes_oportunidade.php?evento_id=<?php echo e($row['evento_id']); ?>" class="btn btn-primary">Ver Detalhes</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Nenhuma oportunidade encontrada com os filtros selecionados.</p>
                <?php endif; ?>
            </div>
        </main>
        </div>
</body>
</html>