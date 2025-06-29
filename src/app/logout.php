<?php
require_once __DIR__ . '/../core/security.php';
secure_session_start();
session_unset();
session_destroy();
header("Location: /kindact/public/");
exit();
?>