<?php
require_once '../controller/clsConexao.php';
class clsItemTreino
{
    private $id;
    private $treino_id;
    private $exercicio_id;
    private $series;
    private $repeticoes;
    private $carga_kg;
    private $observacao;
    private $descanso;
    public function setId($v) { $this->id = $v; }
    public function setTreinoId($v) { $this->treino_id = $v; }
    public function setExercicioId($v) { $this->exercicio_id = $v; }
    public function setSeries($v) { $this->series = $v; }
    public function setRepeticoes($v) { $this->repeticoes = $v; }
    public function setCarga($v) { $this->carga_kg = $v; }
    public function setObservacao($v) { $this->observacao = $v; }
    public function setDescanso($v) { $this->descanso = $v; }
    public function inserir()
    {
        $conexao = new clsConexao();
        $sql = "INSERT INTO itens_treino (treino_id, exercicio_id, series, repeticoes, carga_kg, observacao, descanso) 
                VALUES ('$this->treino_id', '$this->exercicio_id', '$this->series', '$this->repeticoes', '$this->carga_kg', '', '00:00')";
        $conexao->executaSQL($sql);
        $sql_id = "SELECT MAX(id) as id FROM itens_treino";
        $resultado = $conexao->executaSQL($sql_id);
        $dados = mysqli_fetch_assoc($resultado);
        return $dados['id'];
    }
    public function listarDoTreino($id_treino)
    {
        $conexao = new clsConexao();
        $sql = "SELECT it.*, ex.nome as nome_exercicio, ex.grupo_muscular, ex.imagem 
                FROM itens_treino it 
                INNER JOIN exercicios ex ON it.exercicio_id = ex.id 
                WHERE it.treino_id = $id_treino";
        return $conexao->executaSQL($sql);
    }
    public function excluir($id)
    {
        $conexao = new clsConexao();
        $sql = "DELETE FROM itens_treino WHERE id = $id";
        return $conexao->executaSQL($sql);
    }
}
?>