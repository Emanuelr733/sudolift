<?php
session_start();

// Limpa todas as variáveis de sessão
session_unset();

// Destrói a sessão no servidor
session_destroy();

header('Location: ../view/login.php');
exit();
?>