<?php
include 'security.php';
secure_session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'] ?? '';
    $nova_senha = $_POST['password'] ?? '';
    $confirma_senha = $_POST['password_confirm'] ?? '';

    if (empty($token) || empty($nova_senha) || empty($confirma_senha)) {
        header("Location: /kindact/main/login.html?message=" . urlencode("Dados inválidos."));
        exit();
    }

    if ($nova_senha !== $confirma_senha) {
        header("Location: /kindact/main/form_redefinir_senha.php?token={$token}&message=" . urlencode("As senhas não coincidem."));
        exit();
    }

    // Busca o token no banco
    $stmt = $conn->prepare("SELECT * FROM tb_password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: /kindact/main/login.html?message=" . urlencode("Token inválido ou expirado."));
        exit();
    }

    $reset_data = $result->fetch_assoc();
    $email = $reset_data['user_email'];
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    // Atualiza a senha na tabela correta (ong ou voluntario)
    $stmt_update_ong = $conn->prepare("UPDATE tb_ong SET ong_senha = ? WHERE ong_email = ?");
    $stmt_update_ong->bind_param("ss", $senha_hash, $email);
    $stmt_update_ong->execute();

    $stmt_update_vol = $conn->prepare("UPDATE tb_voluntario SET voluntario_senha = ? WHERE voluntario_email = ?");
    $stmt_update_vol->bind_param("ss", $senha_hash, $email);
    $stmt_update_vol->execute();

    // Deleta o token para que não possa ser usado novamente
    $stmt_delete = $conn->prepare("DELETE FROM tb_password_resets WHERE token = ?");
    $stmt_delete->bind_param("s", $token);
    $stmt_delete->execute();

    header("Location: /kindact/main/login.html?message=" . urlencode("Senha redefinida com sucesso! Faça seu login."));
    exit();
}
?>