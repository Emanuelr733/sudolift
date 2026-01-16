<?php
require_once '../controller/clsConexao.php';
class clsExercicio
{
    private $id;
    private $nome;
    private $grupo;
    private $imagem;
    private $equipamento;
    private $grupo_secundario;
    public function setId($v) { $this->id = $v; }
    public function setNome($v) { $this->nome = $v; }
    public function setGrupo($v) { $this->grupo = $v; }
    public function setImagem($v) { $this->imagem = $v; }
    public function setEquipamento($v) { $this->equipamento = $v; }
    public function setGrupoSecundario($v) { $this->grupo_secundario = $v; }
    public function inserir()
    {
        $conexao = new clsConexao();
        $sql = "INSERT INTO exercicios (nome, grupo_muscular, imagem, equipamento, grupo_secundario) 
                VALUES ('$this->nome', '$this->grupo', '$this->imagem', '$this->equipamento', '$this->grupo_secundario')";
        return $conexao->executaSQL($sql);
    }
    public function listar()
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM exercicios ORDER BY nome ASC";
        return $conexao->executaSQL($sql);
    }
    public function excluir($id)
    {
        $conexao = new clsConexao();
        $sql = "DELETE FROM exercicios WHERE id = $id";
        return $conexao->executaSQL($sql);
    }
    public function buscarPorId($id)
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM exercicios WHERE id = $id";
        $resultado = $conexao->executaSQL($sql);
        return mysqli_fetch_assoc($resultado);
    }
}
?>