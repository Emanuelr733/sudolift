<?php
require_once '../controller/clsConexao.php';
class clsTreino
{
    private $id;
    private $usuario_id;
    private $nome_treino;
    private $data_treino;
    private $comentario;
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
        $conexao = new clsConexao();
        $sql = "INSERT INTO treinos (usuario_id, nome_treino, data_treino, comentario) 
                VALUES ('$this->usuario_id', '$this->nome_treino', '$this->data_treino', '$this->comentario')";
        $conexao->executaSQL($sql);
        return $conexao->ultimoID();
    }
    public function listarMeusTreinos($id_do_usuario)
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM treinos WHERE usuario_id = $id_do_usuario ORDER BY data_treino DESC";
        return $conexao->executaSQL($sql);
    }
    public function excluir($id_treino)
    {
        $conexao = new clsConexao();
        $sql_itens = "DELETE FROM itens_treino WHERE treino_id = $id_treino";
        $conexao->executaSQL($sql_itens);
        $sql_treino = "DELETE FROM treinos WHERE id = $id_treino";
        return $conexao->executaSQL($sql_treino);
    }
    public function buscarPorId($id_treino)
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM treinos WHERE id = $id_treino";
        $resultado = $conexao->executaSQL($sql);
        return mysqli_fetch_assoc($resultado);
    }
    public function atualizarNome($id_treino, $novo_nome)
    {
        $conexao = new clsConexao();
        $sql = "UPDATE treinos SET nome_treino = '$novo_nome' WHERE id = $id_treino";
        return $conexao->executaSQL($sql);
    }
}
?>