<?php
// Arquivo: cadastro_ong.php
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $area_atuacao = $_POST['area_atuacao'] ?? '';
    $senha = $_POST['password'] ?? '';

    // --- Nova Validação de Entrada de Dados ---
    if (empty($nome) || empty($cnpj) || empty($telefone) || empty($email) || empty($cep) || empty($endereco) || empty($senha)) {
        header("Location: /kindact/main/cadastro_ong.html?message=" . urlencode("Todos os campos obrigatórios devem ser preenchidos."));
        exit();
    }
    // Validação de formato para email, CNPJ e telefone
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /kindact/main/cadastro_ong.html?message=" . urlencode("Formato de email inválido."));
        exit();
    }
    // Remove caracteres não numéricos do CNPJ e telefone
    $cnpj_clean = preg_replace('/[^0-9]/', '', $cnpj);
    if (!preg_match('/^\d{14}$/', $cnpj_clean)) {
        header("Location: /kindact/main/cadastro_ong.html?message=" . urlencode("Formato de CNPJ inválido. Use apenas números."));
        exit();
    }
    $telefone_clean = preg_replace('/[^0-9]/', '', $telefone);
    if (!preg_match('/^\d{10,11}$/', $telefone_clean)) {
        header("Location: /kindact/main/cadastro_ong.html?message=" . urlencode("Formato de telefone inválido. Use DDD + número (10 ou 11 dígitos)."));
        exit();
    }
    // --- Fim da Validação ---

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO tb_ong (ong_nome, ong_cnpj, ong_telefone, ong_email, ong_cep, ong_endereco, ong_area_atuacao, ong_senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nome, $cnpj_clean, $telefone_clean, $email, $cep, $endereco, $area_atuacao, $senha_hash);

    if ($stmt->execute()) {
        header("Location: /kindact/main/login.html?message=" . urlencode("ONG cadastrada com sucesso! Aguardando aprovação."));
        exit();
    } else {
        header("Location: /kindact/main/cadastro_ong.html?message=" . urlencode("Erro ao cadastrar ONG."));
        exit();
    }

    $stmt->close();
} else {
    header("Location: /kindact/main/cadastro_ong.html?message=" . urlencode("Método não permitido. Use POST."));
    exit();
}
$conn->close();
?>