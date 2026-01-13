<?php
// Arquivo: controller/login_controller.php

// Inicia a sessão para podermos salvar os dados do usuário logado
session_start();

// Chama a classe de Usuário que criamos no passo anterior
require_once '../model/clsUsuario.php';

// Verifica se o usuário chegou aqui enviando o formulário (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recebe os dados do formulário HTML
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Cria um novo objeto Usuário
    $usuario = new clsUsuario();
    
    // Passa os dados para o objeto
    $usuario->setEmail($email);
    $usuario->setSenha($senha);

    // Pergunta pro Model: "Esse usuário existe e a senha tá certa?"
    if ($usuario->logar()) {
        
        // SUCESSO: Salva os dados na memória do navegador (Sessão)
        $_SESSION['id_usuario']     = $usuario->getId();
        $_SESSION['nome_usuario']   = $usuario->getNome();
        $_SESSION['perfil_usuario'] = $usuario->getPerfil();
        $_SESSION['foto_usuario']   = $usuario->getFotoPerfil();

        // Redireciona para o Painel Principal
        header('Location: ../view/dashboard.php');
        exit();
        
    } else {
        
        // ERRO: Devolve para a tela de login com um aviso de erro
        header('Location: ../view/login.php?erro=1');
        exit();
    }
}
?>