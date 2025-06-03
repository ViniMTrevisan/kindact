<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $area_atuacao = $_POST['area_atuacao'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($nome) || empty($cnpj) || empty($telefone) || empty($email) || empty($cep) || empty($endereco) || empty($senha)) {
        die("Todos os campos obrigatórios devem ser preenchidos.");
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO tb_ong (ong_nome, ong_cnpj, ong_telefone, ong_email, ong_cep, ong_endereco, ong_area_atuacao, senha, aprovado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("ssssssss", $nome, $cnpj, $telefone, $email, $cep, $endereco, $area_atuacao, $senha_hash);

    if ($stmt->execute()) {
        echo "ONG cadastrada com sucesso! Aguardando aprovação.";
        header("Location: /kindact/main/login_ong.html");
        exit();
    } else {
        echo "Erro ao cadastrar ONG.";
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Método não permitido. Use POST.";
}
$conn->close();
?>