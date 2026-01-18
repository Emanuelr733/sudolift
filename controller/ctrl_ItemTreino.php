<?php
session_start();
require_once '../model/clsItemTreino.php';
require_once '../model/clsTreino.php';
require_once '../model/clsSerie.php';
require_once '../controller/clsConexao.php'; // Garante que a conexão esteja disponível

// Captura a ação tanto de GET quanto de POST
$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';
$conexao = new clsConexao(); 

switch ($acao) {
    
    case 'excluir':
        $id_item   = (int)$_GET['id_item'];
        $id_treino = (int)$_GET['id_treino'];

        $item = new clsItemTreino();
        $item->excluir($id_item);
        
        header("Location: ../view/treino_detalhes.php?id_treino=" . $id_treino);
        exit();
        break;

    case 'add_set':
        $id_item   = (int)$_GET['id_item'];
        $id_treino = (int)$_GET['id_treino'];

        $serie = new clsSerie();
        $serie->adicionarSerieExtra($id_item);
        
        // Atualiza contador no item
        $conexao->executaSQL("UPDATE itens_treino SET series = series + 1 WHERE id = $id_item");
        
        header("Location: ../view/treino_detalhes.php?id_treino=" . $id_treino);
        exit();
        break;

    case 'remove_set':
        $id_item   = (int)$_GET['id_item'];
        $id_treino = (int)$_GET['id_treino'];

        $serie = new clsSerie();
        $serie->removerUltimaSerie($id_item);
        
        // Atualiza contador no item
        $conexao->executaSQL("UPDATE itens_treino SET series = series - 1 WHERE id = $id_item AND series > 0");
        
        header("Location: ../view/treino_detalhes.php?id_treino=" . $id_treino);
        exit();
        break;

    case 'atualizar_tudo':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_treino = (int)$_POST['treino_id'];

            // Atualiza Nome do Treino
            if (isset($_POST['nome_treino'])) {
                $treino = new clsTreino();
                $treino->atualizarNome($id_treino, $_POST['nome_treino']);
            }

            // Atualiza Observações e Descanso (Itens Pai)
            if (isset($_POST['itens_pai'])) {
                foreach ($_POST['itens_pai'] as $id_item => $dados) {
                    $id_item = (int)$id_item;
                    // addslashes para textos livres
                    $obs  = addslashes($dados['obs']);
                    $desc = addslashes($dados['descanso']);
                    
                    $conexao->executaSQL("UPDATE itens_treino SET observacao = '$obs', descanso = '$desc' WHERE id = $id_item");
                }
            }

            // Atualiza Cargas e Repetições (Séries Individuais)
            if (isset($_POST['series_individuais'])) {
                $objSerie = new clsSerie();
                foreach ($_POST['series_individuais'] as $id_serie => $dadosS) {
                    $objSerie->atualizarSerie($id_serie, $dadosS['carga'], $dadosS['reps']);
                }
            }

            // Adiciona Novo Exercício (se selecionado no select)
            if (isset($_POST['id_exercicio_add']) && !empty($_POST['id_exercicio_add'])) {
                $item = new clsItemTreino();
                $item->setTreinoId($id_treino);
                $item->setExercicioId($_POST['id_exercicio_add']);
                $item->setSeries(3);     // Padrão
                $item->setRepeticoes(10); // Padrão
                $item->setCarga(0);      // Padrão
                
                $novo_id_item = $item->inserir(); 
                
                // Cria as 3 séries filhas
                if ($novo_id_item > 0) {
                    $serie = new clsSerie();
                    $serie->criarSeriesIniciais($novo_id_item);
                }
                
                // Se adicionou exercício, volta para a tela de detalhes para editar
                header("Location: ../view/treino_detalhes.php?id_treino=" . $id_treino);
                exit();
            }

            // Limpa Rascunho se necessário
            if (isset($_SESSION['rascunho_id']) && $_SESSION['rascunho_id'] == $id_treino) {
                unset($_SESSION['rascunho_id']);
            }

            // Finaliza salvamento e vai para a lista de rotinas
            header("Location: ../view/rotinas.php");
            exit();
        }
        break;
        
    default:
        // Se não tiver ação, volta para rotinas
        header("Location: ../view/rotinas.php");
        exit();
        break;
}
?>