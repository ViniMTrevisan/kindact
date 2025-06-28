<?php
// /src/views/admin_dashboard.php
// A segurança já foi verificada pelo roteador, mas podemos chamar de novo para garantir.
require_auth('admin');
$page_title = "Dashboard do Administrador";

// Lógica para buscar dados do banco
$ong_result = $conn->query("SELECT ong_id, ong_nome, ong_email, ong_cnpj, ong_area_atuacao FROM tb_ong WHERE aprovado = 0");
?>

<h2><?php echo e($page_title); ?></h2>
<div id="message-container"></div>

<section id="aprovacao-ongs">
    <h3>Aprovar ONGs</h3>
    <?php if ($ong_result && $ong_result->num_rows > 0): ?>
        <ul class="ong-list">
            <?php while ($row = $ong_result->fetch_assoc()): ?>
                <li class="ong-item">
                    <h4><?php echo e($row['ong_nome']); ?></h4>
                    <p>Email: <?php echo e($row['ong_email']); ?></p>
                    <p>CNPJ: <?php echo e($row['ong_cnpj']); ?></p>
                    
                    <form action="/kindact/src/app/aprovar_ong.php" method="post" style="display:inline-block;">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="ong_id" value="<?php echo e($row['ong_id']); ?>">
                        <button type="submit" class="btn btn-primary">Aprovar</button>
                    </form>
                    
                    <form action="/kindact/src/app/remover_ong.php" method="post" style="display:inline-block;">
                         <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                         <input type="hidden" name="ong_id" value="<?php echo e($row['ong_id']); ?>">
                         <button type="submit" class="btn btn-danger remover">Remover</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Nenhuma ONG pendente de aprovação.</p>
    <?php endif; ?>
</section>