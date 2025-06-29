<?php
// /src/views/login.php
$page_title = "Login";
?>
<h2>Acesse sua conta</h2>
<div id="message-container"></div>

<form action="/kindact/src/app/login_voluntario.php" method="post" class="login-form">
    <h3>Login do Voluntário</h3>
    <div class="form-group"><label for="email_vol">Email:</label><input type="email" id="email_vol" name="email" required></div>
    <div class="form-group"><label for="pass_vol">Senha:</label><input type="password" id="pass_vol" name="password" required></div>
    <button type="submit" class="btn">Entrar como Voluntário</button>
</form>

<form action="/kindact/src/app/login_ong.php" method="post" class="login-form" style="margin-top:20px;">
    <h3>Login da ONG</h3>
    <div class="form-group"><label for="email_ong">Email:</label><input type="email" id="email_ong" name="email" required></div>
    <div class="form-group"><label for="pass_ong">Senha:</label><input type="password" id="pass_ong" name="password" required></div>
    <button type="submit" class="btn">Entrar como ONG</button>
</form>

<div class="extra-links">
    <a href="/kindact/public/index.php?page=form_esqueci_senha">Esqueceu sua senha?</a>
</div>

<div style="text-align: center; margin-top: 30px; font-size: 0.8rem;">
    <a href="/kindact/public/index.php?page=login_admin">Acesso Administrativo</a>
</div>