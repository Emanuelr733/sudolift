<?php
// Arquivo: controller/ctrl_Cadastro.php
session_start();
require_once '../model/clsUsuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // 1. Criptografa a senha (Segurança Obrigatória)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // 2. Lógica de Upload da Foto de Perfil
    $nome_foto = "padrao.png"; // Se não enviar nada, fica essa
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $permitidos = ['jpg', 'jpeg', 'png'];
        
        if (in_array(strtolower($extensao), $permitidos)) {
            // Gera nome único: user_TIMESTAMP.jpg
            $nome_foto = "user_" . time() . "." . $extensao;
            move_uploaded_file($_FILES['foto']['tmp_name'], "../images/" . $nome_foto);
        }
    }

    // 3. Salva no Banco
    $usuario = new clsUsuario();
    $usuario->setNome($nome);
    $usuario->setEmail($email);
    $usuario->setSenha($senha_hash);
    $usuario->setFotoPerfil($nome_foto);

    if ($usuario->cadastrar()) {
        // Redireciona para o login com mensagem de sucesso
        header('Location: ../view/login.php?msg=cadastrado');
    } else {
        echo "Erro ao cadastrar. O e-mail já pode estar em uso.";
    }
}
?>