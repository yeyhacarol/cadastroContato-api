<?php
/* Objetivo: arquivo para criar a conexão com o banco de dados MySQL; autora: Carolina Silva; data de criação: 25/02/2022; 
última modificação: 25/03/2022; versão: 1.0 */

//constantes para estabelecermos a conexão com o bd (local do bd, usuário, senha e database)
const SERVER = 'localhost';
const USER = 'root';
const PASSWORD = 'bcd127';
const DATABASE = 'dbcontatos';

/* testando se a conexão foi realizada!
$resultado = conexaoMySql();
echo('<pre>');
print_r($resultado);
echo('</pre>'); */

// abrir conexão com o banco de dados MySql
function conexaoMySql() {
    $conexao = array();

    //caso a conexão seja estabelecida com o bd, recebremos um array de dados sobre a mesma
    $conexao = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);

    //verificando se a conexão foi realizada com sucesso
    if($conexao) {
        return $conexao;
    } else {
        return false;
    }
    
}

// fechar conexão com o banco de dados MySql, passando a $conexao como argumento/parâmetro, variável existente nos outros arquivos responsável por representar o banco
function   fecharConexaoMySql($conexao)  {
    mysqli_close($conexao);
}

?>