<?php
// Arquivo: admin_dashboard.php
session_start();
// A verificação de sessão garante que apenas admins logados podem acessar a página
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /kindact/main/login.html?message=Acesso%20não%20autorizado.");
    exit();
}

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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.0">
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
            <p>Bem-vindo(a) à sua área de gestão. Utilize as seções abaixo para aprovar novos cadastros e gerenciar usuários.</p>

            <section id="aprovacao-ongs">
                <h3>Aprovar ONGs</h3>
                <?php if ($ong_result->num_rows > 0): ?>
                    <ul class="ong-list">
                        <?php while ($row = $ong_result->fetch_assoc()): ?>
                            <li class="ong-item">
                                <h4><?php echo htmlspecialchars($row['ong_nome']); ?></h4>
                                <p>Email: <?php echo htmlspecialchars($row['ong_email']); ?></p>
                                <p>CNPJ: <?php echo htmlspecialchars($row['ong_cnpj']); ?></p>
                                <p>Área de Atuação: <?php echo htmlspecialchars($row['ong_area_atuacao']); ?></p>
                                <form action="/kindact/php/aprovar_ong.php" method="post" style="display:inline;">
                                    <input type="hidden" name="ong_id" value="<?php echo $row['ong_id']; ?>">
                                    <button type="submit" class="btn btn-primary">Aprovar</button>
                                </form>
                                <a href="/kindact/php/remover_ong.php?ong_id=<?php echo $row['ong_id']; ?>" class="btn btn-danger remover">Remover</a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Nenhuma ONG pendente de aprovação.</p>
                <?php endif; ?>
            </section>
            
            <section id="gerenciar-voluntarios" style="margin-top: 40px;">
                <h3>Gerenciar Voluntários</h3>
                <?php if ($voluntario_result->num_rows > 0): ?>
                    <ul class="voluntario-list">
                        <?php while ($row = $voluntario_result->fetch_assoc()): ?>
                            <li class="voluntario-item">
                                <h4><?php echo htmlspecialchars($row['voluntario_nome']); ?></h4>
                                <p>Email: <?php echo htmlspecialchars($row['voluntario_email']); ?></p>
                                <a href="/kindact/php/remover_voluntario.php?voluntario_id=<?php echo $row['voluntario_id']; ?>" class="btn btn-danger remover">Remover</a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Nenhum voluntário cadastrado.</p>
                <?php endif; ?>
            </section>
        </main>
        <footer class="footer">
            <p class="footer-brand">KindAct</p>
            <p class="footer-text">Juntos, podemos fazer a diferença. Conecte-se, colabore e transforme!</p>
            <div class="footer-links">
                <a href="/kindact/main/termos.html" class="footer-link">Termos</a>
                <a href="/kindact/main/politica_privacidade.html" class="footer-link">Política de Privacidade</a>
            </div>
        </footer>
    </div>
    <script src="/kindact/js/script.js"></script>
</body>
</html>
<?php
$conn->close();
?>