<?php
require_once '../controller/clsConexao.php';

class clsAdmin
{
    // Retorna um array com os totais para os cards
    public function getEstatisticas()
    {
        $conexao = new clsConexao();
        
        // Conta Usuários
        $sqlUsers = "SELECT count(*) as total FROM usuarios";
        $resUsers = mysqli_fetch_assoc($conexao->executaSQL($sqlUsers));
        
        // Conta Exercícios
        $sqlExer = "SELECT count(*) as total FROM exercicios";
        $resExer = mysqli_fetch_assoc($conexao->executaSQL($sqlExer));
        
        return [
            'total_usuarios' => $resUsers['total'],
            'total_exercicios' => $resExer['total']
        ];
    }

    // Retorna a lista completa de usuários
    public function listarUsuarios()
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM usuarios ORDER BY id DESC";
        return $conexao->executaSQL($sql);
    }
}
?>