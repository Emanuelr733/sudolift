<?php
session_start();
require_once '../model/clsUsuario.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $usuario = new clsUsuario();
    $usuario->setEmail($email);
    $usuario->setSenha($senha);
    if ($usuario->logar()) {
        $_SESSION['id_usuario']     = $usuario->getId();
        $_SESSION['nome_usuario']   = $usuario->getNome();
        $_SESSION['perfil_usuario'] = $usuario->getPerfil();
        $_SESSION['foto_usuario']   = $usuario->getFotoPerfil();
        header('Location: ../view/dashboard.php');
        exit();
    } else {
        header('Location: ../view/login.php?erro=1');
        exit();
    }
}
?>