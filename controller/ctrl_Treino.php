<?php
session_start();
require_once '../model/clsTreino.php';
require_once '../model/clsItemTreino.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../view/login.php');
    exit();
}
if (isset($_GET['acao']) && $_GET['acao'] == 'inserir_item') {
    $id_rotina = $_GET['id_rotina'];
    $id_exercicio = $_GET['id_exercicio'];
    if (!empty($id_rotina) && !empty($id_exercicio)) {
        $item = new clsItemTreino();
        $item->inserir($id_rotina, $id_exercicio);
        header("Location: ../view/treino_detalhes.php?id_treino=" . $id_rotina);
        exit();
    }
}
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir_treino') {
    $id = $_GET['id'];    
    if (!empty($id)) {
        $treino = new clsTreino();
        if ($treino->excluir($id)) {
            if(isset($_SESSION['rascunho_id']) && $_SESSION['rascunho_id'] == $id){
                unset($_SESSION['rascunho_id']);
            }
            header("Location: ../view/rotinas.php");
            exit();
        } else {
            echo "Erro ao excluir a rotina.";
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'novo') {
    $nome = $_POST['nome_treino'];

    if(empty($nome)) {
        $nome = "Nova Rotina";
    }
    $treino = new clsTreino();
    $treino->setUsuarioId($_SESSION['id_usuario']);
    $treino->setNomeTreino($nome);
    $treino->setDataTreino(date('Y-m-d')); 
    $treino->setComentario("");
    $id_novo_treino = $treino->inserir();
    if ($id_novo_treino > 0) {
        $_SESSION['rascunho_id'] = $id_novo_treino;
        header("Location: ../view/treino_detalhes.php?id_treino=$id_novo_treino");
    } else {
        echo "Erro ao criar treino.";
    }
    exit();
}
?>