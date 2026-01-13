<?php
// Arquivo: model/clsSerie.php
require_once '../controller/clsConexao.php';

class clsSerie
{
    // 1. INICIALIZAR SÉRIES PADRÃO
    // Quando adicionamos um exercício, criamos 3 séries zeradas automaticamente
    public function criarSeriesIniciais($id_item_treino, $qtd = 3)
    {
        $conexao = new clsConexao();
        for ($i = 1; $i <= $qtd; $i++) {
            $sql = "INSERT INTO treino_series (item_treino_id, numero_serie, carga_kg, repeticoes) 
                    VALUES ($id_item_treino, $i, '0', '10')";
            $conexao->executaSQL($sql);
        }
    }

    // 2. LISTAR SÉRIES DE UM ITEM
    public function listarPorItem($id_item_treino)
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM treino_series WHERE item_treino_id = $id_item_treino ORDER BY numero_serie ASC";
        return $conexao->executaSQL($sql);
    }

    // 3. ADICIONAR UMA NOVA SÉRIE (Botão +)
    public function adicionarSerieExtra($id_item_treino)
    {
        $conexao = new clsConexao();
        // Descobre qual o próximo número da série
        $sqlCount = "SELECT count(*) as total FROM treino_series WHERE item_treino_id = $id_item_treino";
        $res = $conexao->executaSQL($sqlCount);
        $dados = mysqli_fetch_assoc($res);
        $prox = $dados['total'] + 1;

        // Insere repetindo os dados da anterior (pra facilitar) ou zerado
        $sql = "INSERT INTO treino_series (item_treino_id, numero_serie, carga_kg, repeticoes) 
                VALUES ($id_item_treino, $prox, '0', '10')";
        return $conexao->executaSQL($sql);
    }

    // 4. REMOVER A ÚLTIMA SÉRIE
    public function removerUltimaSerie($id_item_treino)
    {
        $conexao = new clsConexao();
        // Deleta a série com o maior número para aquele item
        $sql = "DELETE FROM treino_series WHERE item_treino_id = $id_item_treino ORDER BY numero_serie DESC LIMIT 1";
        return $conexao->executaSQL($sql);
    }

    // 5. ATUALIZAR UMA SÉRIE ESPECÍFICA (Salvar)
    public function atualizarSerie($id_serie, $carga, $reps)
    {
        $conexao = new clsConexao();
        $sql = "UPDATE treino_series SET carga_kg = '$carga', repeticoes = '$reps' WHERE id = $id_serie";
        return $conexao->executaSQL($sql);
    }
}
?>