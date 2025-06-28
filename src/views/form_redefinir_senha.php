<?php
    $token = htmlspecialchars($_GET['token'] ?? '', ENT_QUOTES, 'UTF-8');
    if (empty($token)) {
        die("Token de redefinição não fornecido.");
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/kindact/css/style.css?v=1.1">
    <title>Redefinir Senha</title>
</head>
<body>
    <div class="container">
        <main>
            <h2>Crie sua Nova Senha</h2>
            <div id="message-container"></div>
            <form action="/kindact/php/redefinir_senha.php" method="post">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="form-group">
                    <label for="password">Nova Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirm">Confirme a Nova Senha</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Nova Senha</button>
            </form>
        </main>
    </div>
    <script src="/kindact/js/script.js?v=1.1"></script>
</body>
</html>