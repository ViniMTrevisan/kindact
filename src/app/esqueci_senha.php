<?php
include 'security.php';
secure_session_start();
include 'db_connect.php';
// Para o envio de email, você precisaria de uma biblioteca como o PHPMailer,
// mas para este exemplo, vamos simular e exibir o link na tela.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /kindact/main/form_esqueci_senha.php?message=" . urlencode("Email inválido."));
        exit();
    }

    // Verifica se o email existe em alguma tabela de usuário (ong ou voluntario)
    $stmt_ong = $conn->prepare("SELECT ong_id FROM tb_ong WHERE ong_email = ?");
    $stmt_ong->bind_param("s", $email);
    $stmt_ong->execute();
    $result_ong = $stmt_ong->get_result();

    $stmt_vol = $conn->prepare("SELECT voluntario_id FROM tb_voluntario WHERE voluntario_email = ?");
    $stmt_vol->bind_param("s", $email);
    $stmt_vol->execute();
    $result_vol = $stmt_vol->get_result();

    if ($result_ong->num_rows === 0 && $result_vol->num_rows === 0) {
        header("Location: /kindact/main/form_esqueci_senha.php?message=" . urlencode("Nenhuma conta encontrada com este email."));
        exit();
    }

    // Gera um token seguro
    $token = bin2hex(random_bytes(32));
    $expires = new DateTime('now + 1 hour');
    $expires_at = $expires->format('Y-m-d H:i:s');

    // Salva o token no banco de dados
    $stmt_insert = $conn->prepare("INSERT INTO tb_password_resets (user_email, token, expires_at) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("sss", $email, $token, $expires_at);
    $stmt_insert->execute();

    // **IMPORTANTE: Lógica de Envio de Email**
    // Em um projeto real, você usaria uma biblioteca como PHPMailer aqui.
    // Para este exemplo, vamos apenas exibir o link de redefinição.
    $reset_link = "http://localhost/kindact/main/form_redefinir_senha.php?token=" . $token;

    // Simulação de envio de email
    echo "<h1>Link de Redefinição de Senha (Simulação)</h1>";
    echo "<p>Em um ambiente real, este link seria enviado para o seu email.</p>";
    echo "<p>Copie e cole o link abaixo no seu navegador:</p>";
    echo "<a href='{$reset_link}'>{$reset_link}</a>";

    // header("Location: /kindact/main/login.html?message=" . urlencode("Se o email estiver correto, um link de redefinição foi enviado."));
    exit();
}
?>