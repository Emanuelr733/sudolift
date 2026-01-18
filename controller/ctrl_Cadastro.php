<?php
session_start();
require_once '../model/clsUsuario.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $perfil = $_POST['perfil'] ?? 'atleta';
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $nome_foto = "padrao.png";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $permitidos = ['jpg', 'jpeg', 'png'];
        if (in_array(strtolower($extensao), $permitidos)) {
            $nome_foto = "user_" . time() . "." . $extensao;
            move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/users/" . $nome_foto);
        }
    }
    $usuario = new clsUsuario();
    $usuario->setNome($nome);
    $usuario->setEmail($email);
    $usuario->setSenha($senha_hash);
    $usuario->setFotoPerfil($nome_foto);
    $usuario->setPerfil($perfil);
    if ($usuario->cadastrar()) {
        header('Location: ../view/login.php?msg=cadastrado');
    } else {
        echo "Erro ao cadastrar. O e-mail já pode estar em uso.";
    }
}
?>