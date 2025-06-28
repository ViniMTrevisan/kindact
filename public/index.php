\<?php
// /public/index.php
require_once __DIR__ . '/../src/core/security.php';
secure_session_start();

$page = $_GET['page'] ?? 'index';

$allowed_pages = [
    'index', 'login', 'termos', 'politica_privacidade',
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

if (!file_exists($view_path)) {
    http_response_code(404);
    $page = 'index'; // ou uma página de erro 404
    $view_path = __DIR__ . '/../src/views/index.php';
}

require_once __DIR__ . '/../src/core/db_connect.php';

ob_start();
require $view_path;
$content = ob_get_clean();

// Inclui o cabeçalho e rodapé
// A variável $page_title deve ser definida dentro de cada arquivo de view
require __DIR__ . '/../src/views/partials/header.php';
echo $content;
require __DIR__ . '/../src/views/partials/footer.php';

$conn->close();
?>