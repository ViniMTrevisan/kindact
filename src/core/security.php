<?php
function secure_session_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0, 'path' => '/', 'domain' => '',
            'secure' => isset($_SERVER['HTTPS']), 'httponly' => true, 'samesite' => 'Lax'
        ]);
        session_start();
    }
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) return false;
    $is_valid = hash_equals($_SESSION['csrf_token'], $token);
    unset($_SESSION['csrf_token']);
    return $is_valid;
}

function require_auth(string $required_type) {
    if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== $required_type)) {
        session_unset();
        session_destroy();
        header("Location: /kindact/public/index.php?page=login&message=" . urlencode("Acesso negado."));
        exit();
    }
}
?>