<?php
session_start();
require_once 'clsConexao.php';

// Verifica permissão
if (!isset($_SESSION['perfil_usuario']) || $_SESSION['perfil_usuario'] != 'admin') {
    header('Location: ../view/dashboard.php');
    exit();
}

$conexao = new clsConexao();
$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

switch ($acao) {
    
    case 'alterar_nivel':
        $id = (int)$_POST['id_usuario']; 
        $novo_perfil = $_POST['novo_perfil'];

        // Impede alterar o próprio perfil para não se trancar fora
        if ($id == $_SESSION['id_usuario']) {
            echo "<script>alert('Você não pode alterar seu próprio nível!'); window.location='../view/admin.php';</script>";
            exit();
        }

        // Validação (Lista Branca)
        $perfisPermitidos = ['usuario', 'instrutor', 'escrivao', 'admin'];
        if (!in_array($novo_perfil, $perfisPermitidos)) {
            $novo_perfil = 'usuario';
        }

        $sql = "UPDATE usuarios SET perfil = '$novo_perfil' WHERE id = $id";
        $conexao->executaSQL($sql);
        
        header('Location: ../view/admin.php');
        break;

    case 'excluir_usuario':
        $id = (int)$_GET['id'];

        if ($id == $_SESSION['id_usuario']) {
            echo "<script>alert('Você não pode se excluir!'); window.location='../view/admin.php';</script>";
            exit();
        }

        $sql = "DELETE FROM usuarios WHERE id = $id";
        $conexao->executaSQL($sql);
        
        header('Location: ../view/admin.php');
        break;
}
?>