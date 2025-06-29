<?php
// /public/index.php
require_once __DIR__ . '/../src/core/security.php';
secure_session_start();
require_once __DIR__ . '/../src/core/db_connect.php';

// ROTEADOR DE AÇÕES (POST)
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

// ROTEADOR DE PÁGINAS (GET)
$page = $_GET['page'] ?? 'index';
$allowed_pages = [
    'index', 'login', 'login_admin', 'termos', 'politica_privacidade',
    'form_cadastro_admin', 'form_cadastro_ong', 'form_cadastro_voluntario',
    'form_esqueci_senha', 'form_redefinir_senha',
    'admin_dashboard', 'ong_dashboard', 'voluntario_dashboard',
    'detalhes_ong', 'detalhes_oportunidade', 'gerenciar_candidatos',
    'gerenciar_oportunidade', 'minhas_candidaturas'
];

if (!in_array($page, $allowed_pages)) $page = 'index';
$view_path = __DIR__ . '/../src/views/' . $page . '.php';

if (!file_exists($view_path)) {
    http_response_code(404);
    $page_title = "Erro 404";
    $content = "<h2>Erro 404</h2><p>Página não encontrada.</p>";
} else {
    ob_start();
    require $view_path;
    $content = ob_get_clean();
}

require __DIR__ . '/../src/views/partials/header.php';
// Adiciona o container para o conteúdo principal da página
echo '<div class="container">';
echo $content;
echo '</div>';
require __DIR__ . '/../src/views/partials/footer.php';

if (isset($conn) && $conn instanceof mysqli) $conn->close();
?>