\<?php
require_auth('admin');
$page_title = "Dashboard do Administrador";
$ong_result = $conn->query("SELECT * FROM tb_ong WHERE aprovado = 0");
$voluntario_result = $conn->query("SELECT * FROM tb_voluntario");
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
                    <p>Email: <?php echo e($row['ong_email']); ?> | CNPJ: <?php echo e($row['ong_cnpj']); ?></p>
                    <div class="actions">
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
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Nenhuma ONG pendente de aprovação.</p>
    <?php endif; ?>
</section>

<section id="gerenciar-voluntarios" style="margin-top: 40px;">
    <h3>Gerenciar Voluntários</h3>
    <?php if ($voluntario_result && $voluntario_result->num_rows > 0): ?>
        <ul class="voluntario-list">
            <?php while ($row = $voluntario_result->fetch_assoc()): ?>
                <li class="voluntario-item">
                    <h4><?php echo e($row['voluntario_nome']); ?></h4>
                    <p>Email: <?php echo e($row['voluntario_email']); ?></p>
                    <form action="/kindact/src/app/remover_voluntario.php" method="post" style="display:inline-block;">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="voluntario_id" value="<?php echo e($row['voluntario_id']); ?>">
                        <button type="submit" class="btn btn-danger remover">Remover</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum voluntário cadastrado.</p>
    <?php endif; ?>
</section>