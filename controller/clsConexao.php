<?php
// Arquivo: controller/clsConexao.php

class clsConexao
{
    # VARIAVEIS PRIVADAS
    private $host;
    private $usuario;
    private $senha;
    private $banco;
    private $conexao;
    
    # PROPRIEDADES
    
    # host
    public function setHost($valor)
    {
        $this->host = $valor;
    }
    
    public function getHost()
    {
        return $this->host;
    }
    
    # usuario
    public function setUsuario($valor)
    {
        $this->usuario = $valor;
    }
    
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    # senha
    public function setSenha($valor)
    {
        $this->senha = $valor;
    }
    
    public function getSenha()
    {
        return $this->senha;
    }
    
    # banco
    public function setBanco($valor)
    {
        $this->banco = $valor;
    }
    
    public function getBanco()
    {
        return $this->banco;
    }
    
    # METODOS
    
    # construtor
    function __construct()
    {
        $this->host    = 'localhost';
        $this->usuario = 'root';
        $this->senha   = '';
        $this->banco   = 'sudolift'; // Nome do banco que criamos
        $this->conecta();
    }
    
    /* metodo que usa as informacoes para criar um
       objeto de conexao ao banco */
    public function conecta()
    {
        $this->conexao = mysqli_connect($this->host, $this->usuario, $this->senha, $this->banco);
        
        // Linha extra importante: Força o banco a aceitar acentos (UTF-8)
        if ($this->conexao) {
            mysqli_set_charset($this->conexao, "utf8");
        }
    }
    
    /* metodo que executa uma string SQL no banco de dados */
    public function executaSQL($sql)
    {
        $resposta = mysqli_query($this->conexao, $sql);
        return $resposta;
    }
    
    /* Metodo extra útil: Recuperar o ID do último insert (para quando salvarmos treinos) */
    public function ultimoID()
    {
        return mysqli_insert_id($this->conexao);
    }
}
?>