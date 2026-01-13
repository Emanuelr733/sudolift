<?php
// Arquivo: controller/ctrl_Exercicio.php
session_start();
require_once '../model/clsExercicio.php';

// 1. ROTINA DE EXCLUSÃO
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $exercicio = new clsExercicio();
    
    // (Opcional) Aqui poderíamos deletar o arquivo da pasta images também, 
    // mas vamos manter simples por enquanto.
    
    if ($exercicio->excluir($id)) {
        header('Location: ../view/exercicios_lista.php');
    } else {
        echo "Erro ao excluir.";
    }
    exit();
}

// 2. ROTINA DE CADASTRO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome  = $_POST['nome'];
    $grupo = $_POST['grupo'];
    $tipo  = $_POST['tipo'];
    
    // Variável para guardar o nome do arquivo (começa vazia)
    $nome_arquivo_final = "sem_imagem.png"; 

    // --- LÓGICA DE UPLOAD ---
    // Verifica se veio algum arquivo no campo 'arquivo_midia'
    if (isset($_FILES['arquivo_midia']) && $_FILES['arquivo_midia']['error'] == 0) {
        
        $arquivo_tmp = $_FILES['arquivo_midia']['tmp_name'];
        $nome_original = $_FILES['arquivo_midia']['name'];
        
        // Pega a extensão do arquivo (ex: jpg, mp4)
        $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
        
        // Extensões permitidas
        $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'mp4'];
        
        if (in_array($extensao, $permitidos)) {
            // Gera um nome único para não sobreescrever (ex: exercicio_5f4d... .jpg)
            $nome_arquivo_final = "midia_" . md5(time()) . "." . $extensao;
            
            // Caminho para salvar (pasta 'images' que você criou)
            $destino = "../images/" . $nome_arquivo_final;
            
            // Move o arquivo
            if (move_uploaded_file($arquivo_tmp, $destino)) {
                // Sucesso no upload
            } else {
                echo "Erro ao salvar o arquivo na pasta images.";
                exit();
            }
        } else {
            echo "Extensão não permitida! Use JPG, PNG, GIF ou MP4.";
            exit();
        }
    }

    // Instancia e salva no banco
    $exercicio = new clsExercicio();
    $exercicio->setNome($nome);
    $exercicio->setGrupoMuscular($grupo);
    $exercicio->setTipo($tipo);
    $exercicio->setImagem($nome_arquivo_final); // <--- Salva o nome do arquivo

    if ($exercicio->inserir()) {
        header('Location: ../view/exercicios_lista.php');
    } else {
        echo "Erro ao salvar no banco de dados.";
    }
}
?>