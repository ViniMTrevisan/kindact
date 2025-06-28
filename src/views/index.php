<?php
// /src/views/index.php
$page_title = "Página Inicial";
?>
<section class="hero">
    <h1>KindAct</h1>
    <p class="tagline">Conectando voluntários e ONGs para fazer a diferença!</p>
</section>

<section class="tela">
    <h2>Comece Agora</h2>
    <div class="profile-options" style="display: flex; justify-content: center; gap: 1rem;">
        <a href="/kindact/public/index.php?page=form_cadastro_voluntario" class="btn btn-primary">Sou Voluntário</a>
        <a href="/kindact/public/index.php?page=form_cadastro_ong" class="btn btn-primary">Sou uma ONG</a>
    </div>
</section>

<section class="ajudar">
    <h2>Já tem conta?</h2>
    <div class="login-options" style="display: flex; justify-content: center;">
        <a href="/kindact/public/index.php?page=login" class="btn btn-primary">Fazer Login</a>
    </div>
</section>