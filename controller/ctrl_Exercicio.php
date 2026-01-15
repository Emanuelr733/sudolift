<?php
// Arquivo: controller/ctrl_Exercicio.php
session_start();
// Verifica se é Admin
if (!isset($_SESSION['perfil_usuario']) || $_SESSION['perfil_usuario'] != 'admin') {
    header('Location: ../view/login.php'); exit();
}

require_once '../model/clsExercicio.php';

// --- AÇÃO: SALVAR (Inserir ou Editar) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    
    $acao = $_POST['acao'];
    $id = $_POST['id'];
    
    $nome = $_POST['nome'];
    $grupo_primario = $_POST['grupo_primario'];
    $equipamento = $_POST['equipamento'];
    
    // Tratamento dos Grupos Secundários
    $secundarios = "";
    if (isset($_POST['grupo_secundario'])) {
        $secundarios = implode(", ", $_POST['grupo_secundario']);
    }

    $ex = new clsExercicio();
    
    // --- LÓGICA DE UPLOAD INTELIGENTE ---
    $nome_arquivo = "";
    
    // Se o usuário enviou uma NOVA imagem
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        
        // A. Se for EDIÇÃO, apaga a imagem VELHA primeiro
        if ($acao == 'editar' && $id > 0) {
            $dadosAntigos = $ex->buscarPorId($id);
            $imagemVelha = $dadosAntigos['imagem'];
            
            if (!empty($imagemVelha) && file_exists("../assets/images/exercises/" . $imagemVelha)) {
                unlink("../assets/images/exercises/" . $imagemVelha); // Tchau imagem velha!
            }
        }

        // B. Sobe a imagem NOVA
        $ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = "ex_" . time() . "." . $ext;
        move_uploaded_file($_FILES['arquivo']['tmp_name'], "../assets/images/exercises/" . $nome_arquivo);
        
        // Define a nova imagem no objeto
        $ex->setImagem($nome_arquivo);
    }

    $ex->setNome($nome);
    $ex->setGrupo($grupo_primario);
    $ex->setEquipamento($equipamento);
    $ex->setGrupoSecundario($secundarios);

    if ($acao == 'inserir') {
        $ex->inserir();
    } elseif ($acao == 'editar') {
        $ex->setId($id);
        
        // Atualiza no banco (SQL direto para facilitar update de campos específicos)
        $con = new clsConexao();
        
        // Se subiu imagem nova, atualiza o campo imagem. Se não, mantém a atual.
        $sqlImg = !empty($nome_arquivo) ? ", imagem='$nome_arquivo'" : "";
        
        $sql = "UPDATE exercicios SET 
                nome='$nome', 
                grupo_muscular='$grupo_primario', 
                equipamento='$equipamento', 
                grupo_secundario='$secundarios' 
                $sqlImg 
                WHERE id=$id";
                
        $con->executaSQL($sql);
    }

    header("Location: ../view/exercicios.php?id_ex=" . ($id ? $id : ''));
    exit();
}

// --- AÇÃO: EXCLUIR (Com limpeza de arquivo) ---
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir') {
    $id = $_GET['id'];
    $ex = new clsExercicio();
    
    // 1. Descobre o nome da imagem antes de apagar o registro
    $dadosAntigos = $ex->buscarPorId($id);
    $imagemParaDeletar = $dadosAntigos['imagem'];

    // 2. Se existe imagem e o arquivo existe na pasta, DELETA O ARQUIVO
    if (!empty($imagemParaDeletar) && file_exists("../assets/images/exercises/" . $imagemParaDeletar)) {
        unlink("../assets/images/exercises/" . $imagemParaDeletar); // <--- AQUI APAGA O ARQUIVO FÍSICO
    }

    // 3. Agora sim, apaga do banco
    $ex->excluir($id);
    
    header("Location: ../view/exercicios.php");
    exit();
}
?>