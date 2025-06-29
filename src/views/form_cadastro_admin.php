<?php
// /src/views/form_cadastro_admin.php
$page_title = "Cadastro de Administrador";
?>
<a href="/kindact/public/index.php?page=login" class="back-link">< Voltar</a>
<h2><?php echo e($page_title); ?></h2>
<div id="message-container"></div>

<form action="/kindact/src/app/cadastro_admin.php" method="post" class="form-container">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <div class="form-group">
        <label for="email">Email do Administrador *</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Senha *</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Cadastrar Administrador</button>
</form>