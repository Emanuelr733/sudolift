<?php
session_start();
require_once '../model/clsUsuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpeza básica
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $perfil = $_POST['perfil'] ?? 'atleta'; 

    // Upload de Foto
    $nome_foto = "padrao.png";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $permitidos = ['jpg', 'jpeg', 'png'];
        
        if (in_array(strtolower($extensao), $permitidos)) {
            // Gera nome único para evitar sobrescrever fotos de outros
            $novo_nome = "user_" . uniqid() . "." . $extensao;
            $destino = "../assets/images/users/" . $novo_nome;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $nome_foto = $novo_nome;
            }
        }
    }

    $usuario = new clsUsuario();
    $usuario->setNome($nome);
    $usuario->setEmail($email);
    
    // Passa a senha NORMAL. A classe clsUsuario já vai fazer o hash.
    $usuario->setSenha($senha);
    
    $usuario->setFotoPerfil($nome_foto);
    $usuario->setPerfil($perfil);

    if ($usuario->cadastrar()) {
        // Sucesso
        header('Location: ../view/login.php?msg=cadastrado');
        exit();
    } else {
        // Erro (provavelmente email duplicado)
        // Redireciona de volta para o cadastro com erro
        header('Location: ../view/cadastro.php?erro=Email ja cadastrado');
        exit();
    }
}
?>