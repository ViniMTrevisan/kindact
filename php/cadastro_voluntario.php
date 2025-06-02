<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voluntario_nome = $_POST['name'];
    $voluntario_telefone = $_POST['phone'];
    $voluntario_email = $_POST['email'];
    $voluntario_cep = $_POST['cep'];
    $voluntario_endereco = $_POST['address'];
    $voluntario_senha = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $voluntario_logradouro = $voluntario_endereco;
    $voluntario_numero = '';
    $voluntario_bairro = '';
    $voluntario_complemento = '';
    $voluntario_cidade = '';
    $voluntario_uf = '';

    $stmt = $conn->prepare("INSERT INTO tb_voluntario (voluntario_nome, voluntario_telefone, voluntario_email, voluntario_cep, voluntario_endereco, voluntario_logradouro, voluntario_numero, voluntario_bairro, voluntario_complemento, voluntario_cidade, voluntario_uf, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $voluntario_nome, $voluntario_telefone, $voluntario_email, $voluntario_cep, $voluntario_endereco, $voluntario_logradouro, $voluntario_numero, $voluntario_bairro, $voluntario_complemento, $voluntario_cidade, $voluntario_uf, $voluntario_senha);

    if ($stmt->execute()) {
        header("Location: login_voluntario.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>