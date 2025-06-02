<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ong_nome = $_POST['name'];
    $ong_cnpj = $_POST['cnpj'];
    $ong_telefone = $_POST['phone'];
    $ong_email = $_POST['email'];
    $ong_cep = $_POST['cep'];
    $ong_endereco = $_POST['address'];
    $ong_senha = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $ong_logradouro = $ong_endereco;
    $ong_numero = '';
    $ong_bairro = '';
    $ong_complemento = '';
    $ong_cidade = '';
    $ong_uf = '';
    $ong_area_atuacao = '';
    $aprovado = 0;

    $stmt = $conn->prepare("INSERT INTO tb_ong (ong_nome, ong_cnpj, ong_telefone, ong_email, ong_cep, ong_endereco, ong_logradouro, ong_numero, ong_bairro, ong_complemento, ong_cidade, ong_uf, ong_area_atuacao, senha, aprovado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssi", $ong_nome, $ong_cnpj, $ong_telefone, $ong_email, $ong_cep, $ong_endereco, $ong_logradouro, $ong_numero, $ong_bairro, $ong_complemento, $ong_cidade, $ong_uf, $ong_area_atuacao, $ong_senha, $aprovado);

    if ($stmt->execute()) {
        header("Location: login_ong.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>