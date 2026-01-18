<?php
session_start();
require_once '../model/clsCitacao.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil_usuario'] != 'escrivao') {
    header('Location: ../view/login.php');
    exit();
}

$objCitacao = new clsCitacao();

// Captura a ação vindo de POST ou GET
$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : null;

switch ($acao) {
    case 'inserir':
        $descricao = $_POST['descricao'];
        $autor     = $_POST['autor'];

        if ($objCitacao->inserir($descricao, $autor)) {
            header('Location: ../view/citacoes.php?msg=sucesso');
        } else {
            header('Location: ../view/citacoes.php?msg=erro');
        }
        exit();
        break;

    case 'editar':
        $id        = (int)$_POST['id_citacao'];
        $descricao = $_POST['descricao'];
        $autor     = $_POST['autor'];

        if ($objCitacao->atualizar($id, $descricao, $autor)) {
            header('Location: ../view/citacoes.php?msg=editado');
        } else {
            header('Location: ../view/citacoes.php?msg=erro');
        }
        exit();
        break;

    case 'excluir':
        // No caso de excluir, o ID geralmente vem via GET
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id > 0 && $objCitacao->excluir($id)) {
            header('Location: ../view/citacoes.php?msg=excluido');
        } else {
            header('Location: ../view/citacoes.php?msg=erro');
        }
        exit();
        break;
}
