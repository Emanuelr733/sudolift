<?php
session_start();
if (!isset($_SESSION['perfil_usuario']) || $_SESSION['perfil_usuario'] != 'admin') {
    header('Location: ../view/login.php'); exit();
}
require_once '../model/clsExercicio.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $grupo_primario = $_POST['grupo_primario'];
    $equipamento = $_POST['equipamento'];
    $secundarios = "";
    if (isset($_POST['grupo_secundario'])) {
        $secundarios = implode(", ", $_POST['grupo_secundario']);
    }
    $ex = new clsExercicio();
    $nome_arquivo = "";
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        if ($acao == 'editar' && $id > 0) {
            $dadosAntigos = $ex->buscarPorId($id);
            $imagemVelha = $dadosAntigos['imagem'];
            if (!empty($imagemVelha) && file_exists("../assets/images/exercises/" . $imagemVelha)) {
                unlink("../assets/images/exercises/" . $imagemVelha);
            }
        }
        $ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = "ex_" . time() . "." . $ext;
        move_uploaded_file($_FILES['arquivo']['tmp_name'], "../assets/images/exercises/" . $nome_arquivo);
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
        $con = new clsConexao();
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
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir') {
    $id = $_GET['id'];
    $ex = new clsExercicio();
    $dadosAntigos = $ex->buscarPorId($id);
    $imagemParaDeletar = $dadosAntigos['imagem'];
    if (!empty($imagemParaDeletar) && file_exists("../assets/images/exercises/" . $imagemParaDeletar)) {
        unlink("../assets/images/exercises/" . $imagemParaDeletar);
    }
    $ex->excluir($id);
    header("Location: ../view/exercicios.php");
    exit();
}
?>