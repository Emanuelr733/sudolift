<?php
session_start();
require_once '../model/clsUsuario.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../view/login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha  = $_POST['nova_senha'];
    $usuario = new clsUsuario();
    $usuario->setId($_SESSION['id_usuario']);
    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    if ($usuario->atualizarSenha($nova_senha_hash)) {
        header('Location: ../view/dashboard.php?msg=Senha alterada com sucesso');
    } else {
        echo "Erro ao alterar senha.";
    }
}
?>