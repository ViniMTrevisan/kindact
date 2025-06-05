<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if (!$conn) {
    die("Erro: Conexão com o banco de dados não estabelecida.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /kindact/main/cadastro_admin.html?message=Email%20e%20senha%20são%20obrigatórios.");
        exit();
    }

    try {
        // Testar se a tabela existe
        $result = $conn->query("SHOW TABLES LIKE 'tb_admin'");
        if ($result->num_rows == 0) {
            throw new Exception("Tabela 'tb_admin' não existe no banco de dados.");
        }

        // Verificar se o email já existe
        $stmt = $conn->prepare("SELECT admin_id FROM tb_admin WHERE admin_email = ?");
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta de verificação: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta de verificação: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Erro ao obter o resultado: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            $stmt->close();
            header("Location: /kindact/main/cadastro_admin.html?message=Email%20já%20cadastrado.");
            exit();
        }
        $stmt->close();

        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        if (!$senha_hash) {
            throw new Exception("Erro ao gerar o hash da senha.");
        }

        // Inserir o novo administrador (usando 'senha')
        $stmt = $conn->prepare("INSERT INTO tb_admin (admin_email, senha) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta de inserção: " . $conn->error);
        }

        $stmt->bind_param("ss", $email, $senha_hash);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a inserção: " . $stmt->error);
        }

        $stmt->close();
        header("Location: /kindact/main/login_admin.html?message=Administrador%20cadastrado%20com%20sucesso!");
        exit();
    } catch (Exception $e) {
        if (isset($stmt)) {
            $stmt->close();
        }
        die("Erro: " . $e->getMessage());
    }
} else {
    header("Location: /kindact/main/cadastro_admin.html?message=Método%20não%20permitido.%20Use%20POST.");
    exit();
}

$conn->close();
?>