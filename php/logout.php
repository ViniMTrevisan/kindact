<?php
session_start();
session_destroy();
header("Location: /kindact/main/index.html");
exit();
?>