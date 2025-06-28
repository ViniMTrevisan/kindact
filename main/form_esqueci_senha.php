<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Recuperar Senha</title>
</head>
<body>
    <a href="/kindact/main/login.html" class="back-link">< Voltar para o Login</a>
    <div class="container">
        <main>
            <h2>Esqueceu sua senha?</h2>
            <p>Não se preocupe. Insira seu email abaixo e enviaremos um link para você criar uma nova senha.</p>
            <div id="message-container"></div>
            <form action="/kindact/php/esqueci_senha.php" method="post">
                <div class="form-group">
                    <label for="email">Seu Email de Cadastro</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Link de Recuperação</button>
            </form>
        </main>
    </div>
    <script src="/kindact/js/script.js?v=1.1"></script>
</body>
</html>