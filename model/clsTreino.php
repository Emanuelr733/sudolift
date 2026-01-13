<?php
// Arquivo: model/clsTreino.php
require_once '../controller/clsConexao.php';

class clsTreino
{
    private $id;
    private $usuario_id;
    private $nome_treino;
    private $data_treino;
    private $comentario;

    // Getters e Setters
    public function setId($v) { $this->id = $v; }
    public function getId() { return $this->id; }

    public function setUsuarioId($v) { $this->usuario_id = $v; }
    public function getUsuarioId() { return $this->usuario_id; }

    public function setNomeTreino($v) { $this->nome_treino = $v; }
    public function getNomeTreino() { return $this->nome_treino; }

    public function setDataTreino($v) { $this->data_treino = $v; }
    
    public function setComentario($v) { $this->comentario = $v; }

    // --- MÉTODOS ---

    // 1. INICIAR NOVO TREINO
    public function inserir()
    {
        $conexao = new clsConexao();
        $sql = "INSERT INTO treinos (usuario_id, nome_treino, data_treino, comentario) 
                VALUES ('$this->usuario_id', '$this->nome_treino', '$this->data_treino', '$this->comentario')";
        
        $conexao->executaSQL($sql);
        
        // Retorna o ID desse treino que acabou de ser criado (precisaremos disso para adicionar exercícios nele)
        return $conexao->ultimoID();
    }

    // 2. LISTAR TREINOS DE UM USUÁRIO ESPECÍFICO
    public function listarMeusTreinos($id_do_usuario)
    {
        $conexao = new clsConexao();
        // Ordena do mais recente para o mais antigo
        $sql = "SELECT * FROM treinos WHERE usuario_id = $id_do_usuario ORDER BY data_treino DESC";
        return $conexao->executaSQL($sql);
    }

    // Método para Excluir a Rotina e seus Itens
    public function excluir($id_treino)
    {
        $conexao = new clsConexao();
        
        // ETAPA 1: Apaga todos os exercícios vinculados a este treino
        // (Isso evita erro de chave estrangeira no banco)
        $sql_itens = "DELETE FROM itens_treino WHERE treino_id = $id_treino";
        $conexao->executaSQL($sql_itens);
        
        // ETAPA 2: Agora sim, apaga a rotina (cabeçalho)
        $sql_treino = "DELETE FROM treinos WHERE id = $id_treino";
        return $conexao->executaSQL($sql_treino);
    }

    // 1. Busca os dados de um treino específico (para preencher o input)
    public function buscarPorId($id_treino)
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM treinos WHERE id = $id_treino";
        $resultado = $conexao->executaSQL($sql);
        return mysqli_fetch_assoc($resultado);
    }

    // Método para atualizar apenas o nome do treino
    public function atualizarNome($id_treino, $novo_nome)
    {
        $conexao = new clsConexao();
        // Atenção com as aspas simples '$novo_nome'
        $sql = "UPDATE treinos SET nome_treino = '$novo_nome' WHERE id = $id_treino";
        return $conexao->executaSQL($sql);
    }
}
?>