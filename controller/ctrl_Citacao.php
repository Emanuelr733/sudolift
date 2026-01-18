<?php
session_start();
require_once '../model/clsCitacao.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil_usuario'] != 'escrivao') {
    header('Location: ../view/login.php');
    exit();
}

$objCitacao = new clsCitacao();

if (isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    if ($acao == 'inserir') {
        $descricao = $_POST['descricao'];
        $autor = $_POST['autor'];

        if ($objCitacao->inserir($descricao, $autor)) {
            header('Location: ../view/citacoes.php?msg=sucesso');
        } else {
            header('Location: ../view/citacoes.php?msg=erro');
        }
    } else if ($acao == 'editar') {
        $id = $_POST['id_citacao'];
        $descricao = $_POST['descricao'];
        $autor = $_POST['autor'];

        if ($objCitacao->atualizar($id, $descricao, $autor)) {
            header('Location: ../view/citacoes.php?msg=editado');
        } else {
            header('Location: ../view/citacoes.php?msg=erro');
        }
    }
}

if (isset($_GET['acao']) && $_GET['acao'] == 'excluir') {
    $id = $_GET['id'];
    if ($objCitacao->excluir($id)) {
        header('Location: ../view/citacoes.php?msg=excluido');
    } else {
        header('Location: ../view/citacoes.php?msg=erro');
    }
}
