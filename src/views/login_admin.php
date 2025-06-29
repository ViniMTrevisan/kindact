<?php
// /src/views/login_admin.php
$page_title = "Login do Administrador";
?>

<a href="/kindact/public/index.php?page=login" class="back-link">< Voltar</a>
<h2><?php echo e($page_title); ?></h2>
<p>Esta área é restrita para administradores da plataforma.</p>
<div id="message-container"></div>

<form action="/kindact/src/app/login_admin.php" method="post" class="login-form">
    <div class="form-group">
        <label for="email_admin">Email:</label>
        <input type="email" id="email_admin" name="email" required>
    </div>
    <div class="form-group">
        <label for="pass_admin">Senha:</label>
        <input type="password" id="pass_admin" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Entrar como Administrador</button>
</form>