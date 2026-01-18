<?php
// 1. Configuração para NÃO mostrar erros na tela do usuário (Ideal para deixar profissional)
// Em vez de mostrar o erro, o PHP apenas registra no log do servidor.
ini_set('display_errors', 0); 
error_reporting(E_ALL);

require_once '../model/clsExercicio.php';

$objExercicio = new clsExercicio();

// 2. Captura SEGURA da ação (Resolve o "Undefined variable")
// Tenta pegar do POST, se não tiver, tenta do GET. Se não tiver nenhum, fica vazio.
$acao = "";
if (isset($_POST['acao'])) {
    $acao = $_POST['acao'];
} elseif (isset($_GET['acao'])) {
    $acao = $_GET['acao'];
}

// 3. Se não tiver ação nenhuma, manda de volta para a lista (Evita tela branca)
if (empty($acao)) {
    header('Location: ../view/exercicios.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $equipamento = $_POST['equipamento'];
    
    // Captura apenas os vetores de ativação muscular
    // Se o formulário não enviar nada, define como array vazio
    $vetorMusculos = isset($_POST['musculos_nome']) ? $_POST['musculos_nome'] : [];
    $vetorValores  = isset($_POST['musculos_valor']) ? $_POST['musculos_valor'] : [];

    $ex = new clsExercicio();
    $ex->setNome($nome);
    $ex->setEquipamento($equipamento);

    // --- Lógica de Imagem (Mantida) ---
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        if ($acao == 'editar' && $id > 0) {
            $dados = $ex->buscarPorId($id);
            if (!empty($dados['imagem']) && file_exists("../assets/images/exercises/" . $dados['imagem'])) {
                unlink("../assets/images/exercises/" . $dados['imagem']);
            }
        }
        $ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = "ex_" . time() . "." . $ext;
        
        // Verifica se a pasta existe, se não, cria (opcional, boa prática)
        if (!is_dir("../assets/images/exercises/")) { mkdir("../assets/images/exercises/", 0777, true); }
        
        move_uploaded_file($_FILES['arquivo']['tmp_name'], "../assets/images/exercises/" . $nome_arquivo);
        $ex->setImagem($nome_arquivo);
        
        // Se for edição, atualiza a imagem imediatamente
        if ($acao == 'editar') { $ex->setId($id); $ex->atualizarImagem($nome_arquivo); }
    }

    // --- Fluxo Principal ---
    if ($acao == 'inserir') {
        // 1. Cria exercício e recebe o ID novo
        $novoId = $ex->inserir(); 
        
        // 2. Salva os músculos na tabela auxiliar usando o ID novo
        $ex->salvarMusculos($novoId, $vetorMusculos, $vetorValores);
        
        $idFinal = $novoId;

    } elseif ($acao == 'editar') {
        $ex->setId($id);
        
        // 1. Atualiza dados básicos
        $ex->editar();
        
        // 2. Atualiza os músculos (apaga velhos e insere novos)
        $ex->salvarMusculos($id, $vetorMusculos, $vetorValores);
        
        $idFinal = $id;
    }

    header("Location: ../view/exercicios.php?id_ex=" . $idFinal);
    exit();
}

if ($acao == 'excluir') {
    $id = $_GET['id'];
    
    // 1. Verifica se está em uso ANTES de fazer qualquer coisa
    if ($objExercicio->estaEmUso($id)) {
        // Se estiver em uso, não apaga e avisa o usuário
        echo "<script>
                alert('Não é possível excluir este exercício pois ele faz parte de rotinas de treino de usuários. Remova-o das rotinas antes de excluir.');
                window.location.href = '../view/exercicios.php';
              </script>";
        exit();
    }

    // 2. Busca o nome da imagem para apagar depois (se tiver)
    $imagemParaApagar = $objExercicio->buscarImagem($id);

    // 3. Tenta excluir do banco
    if ($objExercicio->excluir($id)) {
        
        // 4. SUCESSO: Agora sim apagamos a foto da pasta
        if ($imagemParaApagar && file_exists("../assets/images/exercises/" . $imagemParaApagar)) {
            unlink("../assets/images/exercises/" . $imagemParaApagar);
        }

        header('Location: ../view/exercicios.php');
    } else {
        // Erro no banco (não apaga a foto)
        echo "<script>
                alert('Erro ao excluir do banco de dados.');
                window.location.href = '../view/exercicios.php';
              </script>";
    }
}
?>