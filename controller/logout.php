<?php
// Arquivo: controller/logout.php
session_start();

// Destrói todas as variáveis de sessão
session_unset();
session_destroy();

// Manda o usuário de volta para a tela de login
header('Location: ../view/login.php');
exit();
?>