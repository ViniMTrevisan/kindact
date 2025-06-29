<?php
$page_title = "Recuperar Senha";
?>
<a href="/kindact/public/index.php?page=login" class="back-link">< Voltar para o Login</a>
<h2><?php echo e($page_title); ?></h2>
<p>Não se preocupe. Insira seu email abaixo e, se ele estiver em nosso sistema, enviaremos um link para você criar uma nova senha.</p>
<div id="message-container"></div>

<form action="/kindact/src/app/esqueci_senha.php" method="post" class="form-container">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <div class="form-group">
        <label for="email">Seu Email de Cadastro</label>
        <input type="email" id="email" name="email" required>
    </div>
    <button type="submit" class="btn btn-primary">Enviar Link de Recuperação</button>
</form>