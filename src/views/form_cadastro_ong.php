<?php
$page_title = "Cadastro de ONG";
?>
<h2><?php echo e($page_title); ?></h2>
<p>Preencha os dados abaixo para cadastrar sua Organização Não Governamental.</p>
<div id="message-container"></div>

<form action="/kindact/src/app/cadastro_ong.php" method="post" class="form-container">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <div class="form-group"><label for="nome">Nome da ONG:</label><input type="text" id="nome" name="nome" required></div>
    <div class="form-group"><label for="cnpj">CNPJ:</label><input type="text" id="cnpj" name="cnpj" required></div>
    <div class="form-group"><label for="telefone">Telefone:</label><input type="text" id="telefone" name="telefone" required></div>
    <div class="form-group"><label for="email">Email de Contato:</label><input type="email" id="email" name="email" required></div>
    <div class="form-group"><label for="cep">CEP:</label><input type="text" id="cep" name="cep" required></div>
    <div class="form-group"><label for="endereco">Endereço:</label><input type="text" id="endereco" name="endereco" required></div>
    <div class="form-group"><label for="area_atuacao">Área de Atuação Principal:</label><input type="text" id="area_atuacao" name="area_atuacao"></div>
    <div class="form-group"><label for="password">Crie uma Senha:</label><input type="password" id="password" name="password" required></div>
    <button type="submit" class="btn btn-primary">Cadastrar ONG</button>
</form>