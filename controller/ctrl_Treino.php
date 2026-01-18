<?php
session_start();
require_once '../model/clsTreino.php';
require_once '../model/clsItemTreino.php';
require_once '../model/clsSerie.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../view/login.php');
    exit();
}

// Captura ação via GET ou POST
$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

switch ($acao) {
    
    case 'novo':
        // CRIA UMA NOVA ROTINA VAZIA
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome = trim($_POST['nome_treino']);

            if (empty($nome)) {
                $nome = "Nova Rotina";
            }

            $treino = new clsTreino();
            $treino->setUsuarioId($_SESSION['id_usuario']);
            $treino->setNomeTreino($nome);
            $treino->setDataTreino(date('Y-m-d')); 
            $treino->setComentario("");
            
            $id_novo_treino = $treino->inserir();

            if ($id_novo_treino > 0) {
                // Define este ID como rascunho para edição imediata
                $_SESSION['rascunho_id'] = $id_novo_treino;
                header("Location: ../view/treino_detalhes.php?id_treino=$id_novo_treino");
            } else {
                echo "Erro ao criar treino.";
            }
            exit();
        }
        break;

    case 'inserir_item':
        // ADICIONA UM EXERCÍCIO DENTRO DA ROTINA
        $id_rotina    = (int)$_GET['id_rotina'];
        $id_exercicio = (int)$_GET['id_exercicio'];

        if ($id_rotina > 0 && $id_exercicio > 0) {
            $item = new clsItemTreino();
            $item->setTreinoId($id_rotina);
            $item->setExercicioId($id_exercicio);
            
            // Define valores padrão para não quebrar o banco
            $item->setSeries('3');
            $item->setRepeticoes('10');
            $item->setCarga('0');
            $item->setObservacao('');
            $item->setDescanso('00:00');

            $novoIdItem = $item->inserir();
            if ($novoIdItem > 0) {
                $serie = new clsSerie();
                $serie->criarSeriesIniciais($novoIdItem, 3);
            }

            header("Location: ../view/treino_detalhes.php?id_treino=" . $id_rotina);
            exit();
        }
        break;

    case 'excluir_treino':
        // EXCLUI A ROTINA INTEIRA
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id > 0) {
            $treino = new clsTreino();
            // Idealmente, verificar se o treino pertence ao usuário logado antes de excluir
            
            if ($treino->excluir($id)) {
                // Limpa a sessão se apagou o rascunho atual
                if(isset($_SESSION['rascunho_id']) && $_SESSION['rascunho_id'] == $id){
                    unset($_SESSION['rascunho_id']);
                }
                header("Location: ../view/rotinas.php?msg=treino_excluido");
                exit();
            } else {
                echo "Erro ao excluir a rotina.";
            }
        }
        break;
        
    default:
        header("Location: ../view/rotinas.php");
        exit();
        break;
}
?>