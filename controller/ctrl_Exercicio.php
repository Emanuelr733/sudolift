<?php
require_once '../model/clsExercicio.php';
$exercicio = new clsExercicio();

// Captura a ação
$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';
$id   = isset($_REQUEST['id'])   ? (int)$_REQUEST['id'] : 0;

// Se não tiver ação, volta para a lista
if (empty($acao)) {
    header('Location: ../view/exercicios.php');
    exit();
}

switch ($acao) {
    case 'inserir':
    case 'editar':
        // --- LÓGICA DE SALVAR (Inserir ou Editar) ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Define dados básicos
            $exercicio->setNome($_POST['nome']);
            $exercicio->setEquipamento($_POST['equipamento']);
            
            // Captura os vetores de músculos
            $vetorMusculos = isset($_POST['musculos_nome']) ? $_POST['musculos_nome'] : [];
            $vetorValores  = isset($_POST['musculos_valor']) ? $_POST['musculos_valor'] : [];

            // --- LÓGICA DE UPLOAD DE IMAGEM ---
            if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
                
                $ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
                $nome_arquivo = "ex_" . time() . "." . $ext;
                $pasta_destino = "../assets/images/exercises/";

                // Cria pasta se não existir
                if (!is_dir($pasta_destino)) { mkdir($pasta_destino, 0777, true); }
                
                // Tenta fazer o upload
                if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $pasta_destino . $nome_arquivo)) {
                    
                    // Se o upload funcionou e é edição, apaga a foto velha
                    if ($acao == 'editar' && $id > 0) {
                        $dadosAntigos = $exercicio->buscarPorId($id);
                        if (!empty($dadosAntigos['imagem']) && file_exists($pasta_destino . $dadosAntigos['imagem'])) {
                            unlink($pasta_destino . $dadosAntigos['imagem']);
                        }
                    }
                    
                    // Define a nova imagem no objeto
                    $exercicio->setImagem($nome_arquivo);
                    
                    // Se for editar, já atualiza a imagem no banco agora
                    if ($acao == 'editar') {
                        $exercicio->setId($id);
                        $exercicio->atualizarImagem($nome_arquivo);
                    }
                }
            }

            // --- FINALIZAÇÃO NO BANCO ---
            if ($acao == 'inserir') {
                // Insere e pega o novo ID
                $idFinal = $exercicio->inserir();
            } else {
                // Edita os dados de texto
                $exercicio->setId($id);
                $exercicio->editar();
                $idFinal = $id;
            }

            // Salva os músculos (funciona para ambos)
            $exercicio->salvarMusculos($idFinal, $vetorMusculos, $vetorValores);

            header("Location: ../view/exercicios.php?id_ex=" . $idFinal . "&msg=sucesso");
            exit();
        }
        break;

    case 'excluir':
        // --- LÓGICA DE EXCLUSÃO ---
        // Verifica se está em uso (integridade referencial)
        if ($exercicio->estaEmUso($id)) {
            echo "<script>
                    alert('Este exercício faz parte de treinos de usuários e não pode ser excluído.');
                    window.location.href = '../view/exercicios.php';
                  </script>";
            exit();
        }

        // Busca imagem para apagar o arquivo físico
        $imagemParaApagar = $exercicio->buscarImagem($id);

        if ($exercicio->excluir($id)) {
            // Se deletou do banco, apaga o arquivo
            if ($imagemParaApagar && file_exists("../assets/images/exercises/" . $imagemParaApagar)) {
                unlink("../assets/images/exercises/" . $imagemParaApagar);
            }
            header('Location: ../view/exercicios.php?msg=excluido');
        } else {
            echo "<script>
                    alert('Erro ao excluir do banco de dados.');
                    window.location.href = '../view/exercicios.php';
                  </script>";
        }
        break;
}
?>