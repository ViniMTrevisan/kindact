<?php
// /public/index.php (VERSÃO SIMPLIFICADA E CORRIGIDA)

require_once __DIR__ . '/../src/core/security.php';
secure_session_start();
require_once __DIR__ . '/../src/core/db_connect.php';

// ROTEADOR DE AÇÕES (POST) - Esta parte não muda
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    $allowed_actions = [
        'login_admin', 'login_ong', 'login_voluntario',
        'cadastro_admin', 'cadastro_ong', 'cadastro_voluntario',
        'esqueci_senha', 'redefinir_senha',
        'aprovar_ong', 'remover_ong', 'remover_voluntario',
        'gerenciar_oportunidade', 'envio', 'processar_contato'
    ];
    if (in_array($action, $allowed_actions)) {
        $action_path = __DIR__ . '/../src/app/' . $action . '.php';
        if (file_exists($action_path)) {
            require $action_path;
            exit();
        }
    }
}


// ROTEADOR DE PÁGINAS (GET) - Lógica simplificada
$page = $_GET['page'] ?? 'index';
$allowed_pages = [
    'index', 'login', 'login_admin', 'termos', 'politica_privacidade',
    'form_cadastro_admin', 'form_cadastro_ong', 'form_cadastro_voluntario',
    'form_esqueci_senha', 'form_redefinir_senha',
    'admin_dashboard', 'ong_dashboard', 'voluntario_dashboard',
    'detalhes_ong', 'detalhes_oportunidade', 'gerenciar_candidatos',
    'gerenciar_oportunidade', 'minhas_candidaturas'
];

if (!in_array($page, $allowed_pages)) {
    $page = 'index';
}

$view_path = __DIR__ . '/../src/views/' . $page . '.php';

// Define um título padrão
$page_title = ucfirst(str_replace('_', ' ', $page));

// Inclui o cabeçalho
require __DIR__ . '/../src/views/partials/header.php';

// Inclui o conteúdo da página específica
if (file_exists($view_path)) {
    require $view_path;
} else {
    echo "<h2>Erro 404: Página não encontrada</h2>";
}

// Inclui o rodapé
require __DIR__ . '/../src/views/partials/footer.php';

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>