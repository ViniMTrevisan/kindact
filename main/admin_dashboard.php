<?php
// Arquivo: admin_dashboard.php
include '../php/security.php';
secure_session_start();
require_auth('admin'); // Exige que o usuário seja um admin logado

include '../php/db_connect.php';

// Consulta para ONGs pendentes
$ong_sql = "SELECT ong_id, ong_nome, ong_email, ong_cnpj, ong_area_atuacao FROM tb_ong WHERE aprovado = 0";
$ong_result = $conn->query($ong_sql);

// Consulta para todos os voluntários
$voluntario_sql = "SELECT voluntario_id, voluntario_nome, voluntario_email FROM tb_voluntario";
$voluntario_result = $conn->query($voluntario_sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Área do Admin</title>
</head>
<body>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
            <nav class="main-nav">
                <a href="/kindact/php/logout.php" class="btn btn-secondary">Sair</a>
            </nav>
        </header>
        <main>
            <h2>Área do Administrador</h2>
            <div id="message-container" style="margin-bottom: 20px;"></div>

            <section id="aprovacao-ongs">
                <h3>Aprovar ONGs</h3>
                <?php if ($ong_result && $ong_result->num_rows > 0): ?>
                    <ul class="ong-list">
                        <?php while ($row = $ong_result->fetch_assoc()): ?>
                            <li class="ong-item">
                                <h4><?php echo e($row['ong_nome']); ?></h4>
                                <p>Email: <?php echo e($row['ong_email']); ?></p>
                                <p>CNPJ: <?php echo e($row['ong_cnpj']); ?></p>
                                <p>Área de Atuação: <?php echo e($row['ong_area_atuacao']); ?></p>
                                
                                <form action="/kindact/php/aprovar_ong.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="ong_id" value="<?php echo e($row['ong_id']); ?>">
                                    <button type="submit" class="btn btn-primary">Aprovar</button>
                                </form>
                                <form action="/kindact/php/remover_ong.php" method="post" style="display:inline-block;">
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
            
            <section id="gerenciar-voluntarios" style="margin-top: 40px;">
                <h3>Gerenciar Voluntários</h3>
                <?php if ($voluntario_result && $voluntario_result->num_rows > 0): ?>
                    <ul class="voluntario-list">
                        <?php while ($row = $voluntario_result->fetch_assoc()): ?>
                            <li class="voluntario-item">
                                <h4><?php echo e($row['voluntario_nome']); ?></h4>
                                <p>Email: <?php echo e($row['voluntario_email']); ?></p>
                                <form action="/kindact/php/remover_voluntario.php" method="post" style="display:inline-block;">
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
        </main>
        </div>
    <script src="/kindact/js/script.js?v=1.1"></script>
</body>
</html>
<?php $conn->close(); ?>