<?php
// Arquivo: controller/ctrl_Treino.php
session_start();
require_once '../model/clsTreino.php';

// Verifica se usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../view/login.php');
    exit();
}

// 1. AÇÃO: EXCLUIR ROTINA (Vem do botão "Excluir" do menu)
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir_treino') {
    $id = $_GET['id'];
    
    if (!empty($id)) {
        $treino = new clsTreino();
        if ($treino->excluir($id)) {
            header("Location: ../view/rotinas.php");
            exit();
        } else {
            echo "Erro ao excluir a rotina.";
        }
    }
}

// 2. AÇÃO: CRIAR NOVA ROTINA (Vem do botão "Nova Rotina")
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'novo') {
    
    $nome = $_POST['nome_treino'];
    
    // Se por acaso vier vazio, coloca um nome padrão
    if(empty($nome)) {
        $nome = "Nova Rotina";
    }

    $treino = new clsTreino();
    $treino->setUsuarioId($_SESSION['id_usuario']);
    $treino->setNomeTreino($nome);
    $treino->setDataTreino(date('Y-m-d')); 
    $treino->setComentario("");

    // Salva e recebe o ID do treino recém-criado
    $id_novo_treino = $treino->inserir();

    if ($id_novo_treino > 0) {
        // --- NOVO: Marca este ID como RASCUNHO na sessão ---
        $_SESSION['rascunho_id'] = $id_novo_treino;
        header("Location: ../view/treino_detalhes.php?id_treino=$id_novo_treino");
    } else {
        echo "Erro ao criar treino.";
    }
    exit();
}
?>