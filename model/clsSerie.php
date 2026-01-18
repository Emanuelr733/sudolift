<?php
require_once '../controller/clsConexao.php';

class clsSerie
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = new clsConexao();
    }

    public function criarSeriesIniciais($id_item_treino, $qtd = 3)
    {
        $id_item_treino = (int)$id_item_treino;
        
        for ($i = 1; $i <= $qtd; $i++) {
            // Insere 3 séries padrão com carga 0 e 10 repetições
            $sql = "INSERT INTO treino_series (item_treino_id, numero_serie, carga_kg, repeticoes) 
                    VALUES ($id_item_treino, $i, '0', '10')";
            $this->conexao->executaSQL($sql);
        }
    }

    public function listarPorItem($id_item_treino)
    {
        $id_item_treino = (int)$id_item_treino;
        $sql = "SELECT * FROM treino_series WHERE item_treino_id = $id_item_treino ORDER BY numero_serie ASC";
        return $this->conexao->executaSQL($sql);
    }

    public function adicionarSerieExtra($id_item_treino)
    {
        $id_item_treino = (int)$id_item_treino;
        
        // 2. MELHORIA DE LÓGICA:
        // Usamos MAX em vez de COUNT. Se apagar uma série do meio, o COUNT geraria numero repetido.
        // O COALESCE(..., 0) garante que se não houver nenhuma, começa do 0 + 1 = 1.
        $sqlMax = "SELECT COALESCE(MAX(numero_serie), 0) + 1 as proximo 
                   FROM treino_series 
                   WHERE item_treino_id = $id_item_treino";
                   
        $res = $this->conexao->executaSQL($sqlMax);
        $dados = mysqli_fetch_assoc($res);
        $prox = $dados['proximo'];

        $sql = "INSERT INTO treino_series (item_treino_id, numero_serie, carga_kg, repeticoes) 
                VALUES ($id_item_treino, $prox, '0', '10')";
                
        return $this->conexao->executaSQL($sql);
    }

    public function removerUltimaSerie($id_item_treino)
    {
        $id_item_treino = (int)$id_item_treino;
        
        // Remove apenas a de maior numeração
        $sql = "DELETE FROM treino_series 
                WHERE item_treino_id = $id_item_treino 
                ORDER BY numero_serie DESC 
                LIMIT 1";
                
        return $this->conexao->executaSQL($sql);
    }

    public function atualizarSerie($id_serie, $carga, $reps)
    {
        $id_serie = (int)$id_serie;
        
        $carga = mysqli_real_escape_string($this->conexao->getConexao(), $carga);
        $reps  = mysqli_real_escape_string($this->conexao->getConexao(), $reps);

        $sql = "UPDATE treino_series SET carga_kg = '$carga', repeticoes = '$reps' WHERE id = $id_serie";
        return $this->conexao->executaSQL($sql);
    }
}
?>