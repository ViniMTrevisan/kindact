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
    <p>Crie sua conta e faça parte da nossa comunidade.</p>
    <div class="profile-options" style="display: flex; justify-content: center; gap: 1rem; margin-top: 15px;">
        <a href="/kindact/public/index.php?page=form_cadastro_voluntario" class="btn btn-primary">Quero ser Voluntário</a>
        <a href="/kindact/public/index.php?page=form_cadastro_ong" class="btn btn-primary">Quero cadastrar minha ONG</a>
    </div>
</section>

<section class="ajudar" style="margin-top: 40px;">
    <h2>Já tem conta?</h2>
    <p>Faça login para continuar sua jornada e gerenciar suas atividades.</p>
    <div class="login-options" style="display: flex; justify-content: center; margin-top: 15px;">
        <a href="/kindact/public/index.php?page=login" class="btn btn-secondary">Fazer Login</a>
    </div>
</section>