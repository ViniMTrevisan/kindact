<?php
// Arquivo: /kindact/php/security.php

/**
 * Inicia a sessão de forma segura, regenerando o ID para prevenir ataques de Session Fixation.
 * Deve ser chamado no início de cada página que usa sessões.
 */
function secure_session_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Previne Session Fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

/**
 * Escapa a saída de dados para o HTML para prevenir ataques XSS.
 * É um atalho para htmlspecialchars.
 *
 * @param string|null $string A string a ser escapada.
 * @return string A string segura para exibição.
 */
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Gera um token CSRF e o armazena na sessão.
 *
 * @return string O token gerado.
 */
function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida o token CSRF enviado contra o armazenado na sessão.
 *
 * @param string|null $token O token recebido do formulário.
 * @return bool True se o token for válido, false caso contrário.
 */
function validate_csrf_token(?string $token): bool {
    if (!empty($token) && !empty($_SESSION['csrf_token'])) {
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    return false;
}

/**
 * Verifica se o usuário está autenticado e tem a permissão necessária.
 * Redireciona para o login se não estiver autorizado.
 *
 * @param string $required_type O tipo de usuário necessário ('admin', 'ong', 'voluntario').
 */
function require_auth(string $required_type) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== $required_type) {
        // Limpa a sessão para garantir que não haja dados residuais.
        session_unset();
        session_destroy();
        // Redireciona para a página de login com uma mensagem de erro.
        header("Location: /kindact/main/login.html?message=" . urlencode("Acesso negado. Por favor, faça login."));
        exit();
    }
}
?>