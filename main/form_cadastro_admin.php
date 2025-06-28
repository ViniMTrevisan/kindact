<?php
    // Inclui o novo arquivo de segurança para usar suas funções
    include '../php/security.php';
    // Inicia a sessão de forma segura para gerar o token
    secure_session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Cadastro - Administrador</title>
</head>
<body>
    <a href="/kindact/main/login.html" class="back-link" aria-label="Voltar para o login">< Voltar</a>
    <div class="container">
        <header class="header">
            <a href="/kindact/main/index.html" class="logo">KindAct</a>
        </header>
        <main>
            <h2>Cadastro de Administrador</h2>
            <div id="message-container"></div>
            <form action="/kindact/php/cadastro_admin.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha *</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>
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
    <script src="/kindact/js/script.js?v=1.1"></script>
</body>
</html>