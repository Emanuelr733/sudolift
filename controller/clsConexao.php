<?php
class clsConexao
{
    private $host;
    private $usuario;
    private $senha;
    private $banco;
    private $conexao;
    public function setHost($valor){$this->host = $valor;}
    public function getHost(){return $this->host;}
    public function setUsuario($valor){$this->usuario = $valor;}
    public function getUsuario(){return $this->usuario;}
    public function setSenha($valor){$this->senha = $valor;}
    public function getSenha(){return $this->senha;}
    public function setBanco($valor){$this->banco = $valor;}
    public function getBanco(){return $this->banco;}
    function __construct()
    {
        $this->host    = 'localhost';
        $this->usuario = 'root';
        $this->senha   = '';
        $this->banco   = 'sudolift';
        $this->conecta();
    }
    public function conecta()
    {
        $this->conexao = mysqli_connect($this->host, $this->usuario, $this->senha, $this->banco);
        if ($this->conexao) {
            mysqli_set_charset($this->conexao, "utf8");
        }
    }
    public function executaSQL($sql)
    {
        $resposta = mysqli_query($this->conexao, $sql);
        return $resposta;
    }
    public function ultimoID(){
        return mysqli_insert_id($this->conexao);
    }
    public function getConexao() {
        return $this->conexao;
    }
}
?>