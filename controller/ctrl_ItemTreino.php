<?php
session_start();
require_once '../model/clsItemTreino.php';
require_once '../model/clsTreino.php';
require_once '../model/clsSerie.php';
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir') {
    $item = new clsItemTreino();
    $item->excluir($_GET['id_item']);
    header("Location: ../view/treino_detalhes.php?id_treino=" . $_GET['id_treino']);
    exit();
}
if (isset($_GET['acao']) && $_GET['acao'] == 'add_set') {
    $serie = new clsSerie();
    $serie->adicionarSerieExtra($_GET['id_item']);
    $conexao = new clsConexao();
    $conexao->executaSQL("UPDATE itens_treino SET series = series + 1 WHERE id = " . $_GET['id_item']);
    header("Location: ../view/treino_detalhes.php?id_treino=" . $_GET['id_treino']);
    exit();
}
if (isset($_GET['acao']) && $_GET['acao'] == 'remove_set') {
    $serie = new clsSerie();
    $serie->removerUltimaSerie($_GET['id_item']);
    $conexao = new clsConexao();
    $conexao->executaSQL("UPDATE itens_treino SET series = series - 1 WHERE id = " . $_GET['id_item'] . " AND series > 0");
    header("Location: ../view/treino_detalhes.php?id_treino=" . $_GET['id_treino']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'atualizar_tudo') {
    
    $id_treino = $_POST['treino_id'];
    if (isset($_POST['nome_treino'])) {
        $treino = new clsTreino();
        $treino->atualizarNome($id_treino, $_POST['nome_treino']);
    }
    if (isset($_POST['itens_pai'])) {
        $con = new clsConexao();
        foreach ($_POST['itens_pai'] as $id_item => $dados) {
            $obs = addslashes($dados['obs']);
            $desc = addslashes($dados['descanso']);
            $con->executaSQL("UPDATE itens_treino SET observacao = '$obs', descanso = '$desc' WHERE id = $id_item");
        }
    }
    if (isset($_POST['series_individuais'])) {
        $objSerie = new clsSerie();
        foreach ($_POST['series_individuais'] as $id_serie => $dadosS) {
            $objSerie->atualizarSerie($id_serie, $dadosS['carga'], $dadosS['reps']);
        }
    }
    if (isset($_POST['id_exercicio_add']) && !empty($_POST['id_exercicio_add'])) {
        $item = new clsItemTreino();
        $item->setTreinoId($id_treino);
        $item->setExercicioId($_POST['id_exercicio_add']);
        $item->setSeries(3);
        $item->setRepeticoes(10);
        $item->setCarga(0);
        $novo_id_item = $item->inserir(); 
        if ($novo_id_item > 0) {
            $serie = new clsSerie();
            $serie->criarSeriesIniciais($novo_id_item);
        }
        header("Location: ../view/treino_detalhes.php?id_treino=" . $id_treino);
        exit();
    }
    if (isset($_SESSION['rascunho_id']) && $_SESSION['rascunho_id'] == $id_treino) {
        unset($_SESSION['rascunho_id']);
    }
    header("Location: ../view/rotinas.php");
    exit();
}
?>