<?php $page_title = "Cadastro de Voluntário"; ?>
<h2><?php echo e($page_title); ?></h2>
<p>Junte-se a nós! Preencha seus dados para começar a fazer a diferença.</p>
<div id="message-container"></div>

<form action="/kindact/src/app/cadastro_voluntario.php" method="post" class="form-container">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <div class="form-group"><label for="nome">Nome Completo:</label><input type="text" id="nome" name="nome" required></div>
    <div class="form-group"><label for="telefone">Telefone (com DDD):</label><input type="text" id="telefone" name="telefone" required></div>
    <div class="form-group"><label for="email">Seu Melhor Email:</label><input type="email" id="email" name="email" required></div>
    <div class="form-group"><label for="cep">CEP:</label><input type="text" id="cep" name="cep" required></div>
    <div class="form-group"><label for="endereco">Endereço:</label><input type="text" id="endereco" name="endereco" required></div>
    <div class="form-group"><label for="password">Crie uma Senha:</label><input type="password" id="password" name="password" required></div>
    <button type="submit" class="btn btn-primary">Finalizar Cadastro</button>
</form>