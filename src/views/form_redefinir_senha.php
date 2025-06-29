<?php
$page_title = "Redefinir Senha";
$token = $_GET['token'] ?? '';
?>
<h2>Crie sua Nova Senha</h2>
<div id="message-container"></div>

<form action="/kindact/src/app/redefinir_senha.php" method="post" class="form-container">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="hidden" name="token" value="<?php echo e($token); ?>">
    
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