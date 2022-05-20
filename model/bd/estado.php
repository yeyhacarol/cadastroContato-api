<?php
/* Objetivo: arquivo responsável pela manipulação dos dados dentro do banco de dados; autora; Carolina Silva; data de crição: 10/05/2022; versão: 1.0 */

require_once('conexaoMySql.php');

/* função para listar estados do banco */
function selectAllEstados()
{
    //abrindo conexão com o banco
    $conexao = conexaoMySql();

    //script para listar tudo da tabela presente no banco de dados
    $sql = "select * from tblEstados order by nome asc";
    //executa o script e armazena o retorno dos dados
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        /* while desta maneira percorre e converte cada um dos dados da lista que o banco nos traz para um array até o último dado. 
               criamos uma variável que armazenará a conversão que o mysqli_fetch_assoc() fará na nossa variável que resgata os valores do banco $result.
               mysqli_fetch_assoc para convertermos a lista que o banco nos traz para o formato de array. gerencia a quantidade de itens do array */
        //cont para gerenciar os dados e não sobrescrever um ao outro.
        $cont = 0;
        while ($resultDados = mysqli_fetch_assoc($result)) {
            /*criando array com os dados do banco de dados determinando a chave que possui maior semântica. 
                  lembrando que a recepção dos dados do banco foram convertidos para um array e por isso o retorno que temos é em formato de chave considerando os nomes dados lá no banco */
            $arrayDados[$cont] = array(
                "idEstado"       => $resultDados['idEstado'],
                "nome"     => $resultDados['nome'],
                "sigla"    => $resultDados['sigla']
            );

            $cont++;
        }

        //função para fechar a conexão informando qual é o banco, nesse caso armazenado na variável $conexao
        fecharConexaoMySql($conexao);

        return $arrayDados;
    }
}

?>