<?php
session_start();
require_once 'clsConexao.php';

// Segurança: Só admin entra aqui
if (!isset($_SESSION['perfil_usuario']) || $_SESSION['perfil_usuario'] != 'admin') {
    header('Location: ../view/dashboard.php');
    exit();
}

$conexao = new clsConexao();
$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

if ($acao == 'trocar_perfil') {
    $id = $_POST['id_usuario'];
    $novo_perfil = $_POST['novo_perfil'];
    
    // Evita que o usuário tire o próprio admin
    if($id == $_SESSION['id_usuario']) {
        echo "<script>alert('Você não pode alterar seu próprio perfil!'); window.location='../view/admin.php';</script>";
        exit();
    }

    $sql = "UPDATE usuarios SET perfil = '$novo_perfil' WHERE id = $id";
    $conexao->executaSQL($sql);
    header('Location: ../view/admin.php');
}

if ($acao == 'alterar_nivel') {
    $id = $_POST['id_usuario'];
    $novo_perfil = $_POST['novo_perfil'];
    
    // 1. Proteção: Não deixa alterar o próprio perfil
    if($id == $_SESSION['id_usuario']) {
        echo "<script>alert('Você não pode alterar seu próprio nível de acesso!'); window.location='../view/admin.php';</script>";
        exit();
    }

    // 2. Proteção: Garante que o valor enviado é válido (Evita injeção de valores estranhos)
    $perfisPermitidos = ['usuario', 'instrutor', 'escrivao', 'admin'];
    if (!in_array($novo_perfil, $perfisPermitidos)) {
        // Se tentarem enviar "hacker" ou algo assim, forçamos "usuario"
        $novo_perfil = 'usuario';
    }

    // 3. Atualiza no Banco
    // ATENÇÃO: Verifique se sua coluna chama 'perfil_usuario' ou 'perfil'
    $sql = "UPDATE usuarios SET perfil = '$novo_perfil' WHERE id = $id";
    
    if ($conexao->executaSQL($sql)) {
        header('Location: ../view/admin.php');
    } else {
        echo "<script>alert('Erro ao atualizar!'); window.location='../view/admin.php';</script>";
    }
}

if ($acao == 'excluir_usuario') {
    $id = $_GET['id'];
    
    // Segurança extra
    if($id == $_SESSION['id_usuario']) exit();

    // 1. Apaga treinos do usuário
    // (Dependendo das chaves estrangeiras, talvez precise apagar itens_treino e series antes,
    // mas se estiver com CASCADE no banco, só deletar o usuário resolve tudo).
    
    $sql = "DELETE FROM usuarios WHERE id = $id";
    $conexao->executaSQL($sql);
    
    header('Location: ../view/admin.php');
}
?>