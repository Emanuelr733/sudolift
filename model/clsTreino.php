<?php
require_once '../controller/clsConexao.php';

class clsTreino
{
    private $id;
    private $usuario_id;
    private $nome_treino;
    private $data_treino;
    private $comentario;
    
    private $conexao;

    public function __construct()
    {
        $this->conexao = new clsConexao();
    }

    public function setId($v) { $this->id = $v; }
    public function getId() { return $this->id; }

    public function setUsuarioId($v) { $this->usuario_id = $v; }
    public function getUsuarioId() { return $this->usuario_id; }
    
    public function setNomeTreino($v) { $this->nome_treino = $v; }
    public function getNomeTreino() { return $this->nome_treino; }
    
    public function setDataTreino($v) { $this->data_treino = $v; }
    
    public function setComentario($v) { $this->comentario = $v; }

    public function inserir()
    {
        $usuario_id = (int)$this->usuario_id;
        
        $nome = mysqli_real_escape_string($this->conexao->getConexao(), $this->nome_treino);
        $data = mysqli_real_escape_string($this->conexao->getConexao(), $this->data_treino);
        $comen = mysqli_real_escape_string($this->conexao->getConexao(), $this->comentario);

        $sql = "INSERT INTO treinos (usuario_id, nome_treino, data_treino, comentario) 
                VALUES ($usuario_id, '$nome', '$data', '$comen')";
        
        $this->conexao->executaSQL($sql);
        
        return $this->conexao->ultimoID();
    }

    public function listarMeusTreinos($id_do_usuario)
    {
        $id_do_usuario = (int)$id_do_usuario;
        
        $sql = "SELECT * FROM treinos WHERE usuario_id = $id_do_usuario ORDER BY data_treino DESC";
        return $this->conexao->executaSQL($sql);
    }

    public function excluir($id_treino)
    {
        $id_treino = (int)$id_treino;

        // 1. Apaga os itens do treino (exercícios vinculados)
        $sql_itens = "DELETE FROM itens_treino WHERE treino_id = $id_treino";
        $this->conexao->executaSQL($sql_itens);
        
        // 2. Apaga o treino em si
        $sql_treino = "DELETE FROM treinos WHERE id = $id_treino";
        return $this->conexao->executaSQL($sql_treino);
    }

    public function buscarPorId($id_treino)
    {
        $id_treino = (int)$id_treino;
        
        $sql = "SELECT * FROM treinos WHERE id = $id_treino";
        $resultado = $this->conexao->executaSQL($sql);
        return mysqli_fetch_assoc($resultado);
    }

    public function atualizarNome($id_treino, $novo_nome)
    {
        $id_treino = (int)$id_treino;
        $novo_nome = mysqli_real_escape_string($this->conexao->getConexao(), $novo_nome);

        $sql = "UPDATE treinos SET nome_treino = '$novo_nome' WHERE id = $id_treino";
        return $this->conexao->executaSQL($sql);
    }
}
?>