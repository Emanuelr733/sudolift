<?php
// Arquivo: model/clsItemTreino.php
require_once '../controller/clsConexao.php';

class clsItemTreino
{
    private $treino_id;
    private $exercicio_id;
    private $series;
    private $repeticoes;
    private $carga;

    // Setters
    public function setTreinoId($v) { $this->treino_id = $v; }
    public function setExercicioId($v) { $this->exercicio_id = $v; }
    public function setSeries($v) { $this->series = $v; }
    public function setRepeticoes($v) { $this->repeticoes = $v; }
    public function setCarga($v) { $this->carga = $v; }

    // --- MÉTODOS CRUD ---

    // 1. ADICIONAR ITEM AO TREINO
    public function inserir()
    {
        $conexao = new clsConexao();
        $sql = "INSERT INTO itens_treino (treino_id, exercicio_id, series, repeticoes, carga_kg) 
                VALUES ('$this->treino_id', '$this->exercicio_id', '$this->series', '$this->repeticoes', '$this->carga')";
        return $conexao->executaSQL($sql);
    }

    // 2. LISTAR ITENS DESTE TREINO (Com JOIN para pegar o nome do exercício)
    public function listarDoTreino($id_treino)
    {
        $conexao = new clsConexao();
        // Aqui usamos um JOIN para trazer o nome do exercício junto com a carga
        $sql = "SELECT i.*, e.nome as nome_exercicio, e.grupo_muscular 
                FROM itens_treino i
                INNER JOIN exercicios e ON i.exercicio_id = e.id
                WHERE i.treino_id = $id_treino
                ORDER BY i.id DESC";
        return $conexao->executaSQL($sql);
    }

    // 3. REMOVER ITEM (Caso errou a carga)
    public function excluir($id_item)
    {
        $conexao = new clsConexao();
        $sql = "DELETE FROM itens_treino WHERE id = $id_item";
        return $conexao->executaSQL($sql);
    }
}
?>