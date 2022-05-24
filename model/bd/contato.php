<?php
/* Objetivo: arquivo responsável pela manipulação dos dados dentro do banco de dados; autora; Carolina Silva; data de crição: 11/03/2022; 
última modificação: 26/04/2022; versão: 1.0  */

//import do arquivo que estabelece a conexão com o banco de dados
require_once('conexaoMySql.php');

//função para inserção no banco de dados
function insertContato($dadosContato)
{
    //variável para ser utilizada no return da função. dessa maneira conseguiremos eliminar os else's da função, pois ela sempre será false a não ser que seja true
    $statusResposta = (bool) false;

    //abrir a conexão com o banco de dados, só assim poderemos fazer a inserção
    $conexao = conexaoMySql();

    //variável para armazenar o script do banco
    $sql = "insert into tblcontatos
                 (nome,
                  telefone,
                  celular,
                  email,
                  obs,
                  foto,
                  idEstado)
                  
                values
                 ('" . $dadosContato['nome'] . "',
                  '" . $dadosContato['telefone'] . "',
                  '" . $dadosContato['celular'] . "',
                  '" . $dadosContato['email'] . "',
                  '" . $dadosContato['obs'] . "',
                  '" . $dadosContato['foto'] ."',
                  '" . $dadosContato['idEstado'] . "');";

    //executar o script no banco. _query é a função para encaminhar o script para o banco que retorna um booleano
    //primeira validação para sabermos se o script sql está correto ou não
    if (mysqli_query($conexao, $sql)) {
        //validando se houve alguma modificação; linha acrescentada no banco de dados
        if (mysqli_affected_rows($conexao)) {
            $statusResposta = true;
        } /* eis os else's que podem ser eliminados com a declaração daquela variável:
                else {
                    $statusResposta = false;
                }
            } else {
                $statusResposta = false; 
            } */
    }

    fecharConexaoMySql($conexao);
    return $statusResposta;
}

//função para excluir no banco de dados
function deleteContato($id)
{
    //variável para armazenar o status da conexão, se houve affected_rows ou não(false)!
    $statusResposta = (bool) false;

    //abrindo conexão com o banco de dados
    $conexao = conexaoMySql();

    $sql = "delete from tblcontatos where idcontato=" . $id;

    //validando se o script está correto e executando o banco através do _query
    if (mysqli_query($conexao, $sql)) {
        //verificando se houveram linhas afetadas, ou seja, se a modificação foi feita com sucesso
        if (mysqli_affected_rows($conexao)) {
            $statusResposta = true;
        }
    }

    //fechando a conexão com o banco
    fecharConexaoMySql($conexao);
    return $statusResposta;
}

//função para buscar contato no banco de dados através do id
function selectByIdContato($id)
{
    //abrindo conexão com o banco de dados
    $conexao = conexaoMySql();

    //script para listar os contatos da tabela presente no banco de dados referenciando o id
    $sql = "select
            tblcontatos.idContato,
            tblcontatos.nome as nomeContato,
            tblcontatos.telefone,
            tblcontatos.celular,
            tblcontatos.email,
            tblcontatos.obs,
            tblcontatos.foto,
            tblestados.idEstado,
            tblestados.sigla,
            tblestados.nome as nomeEstado
        from tblcontatos inner join tblestados on tblcontatos.idEstado = tblestados.idEstado where tblcontatos.idContato = " . $id;
    //executa o script e armazena o retorno dos dados
    $result = mysqli_query($conexao, $sql);

    $arrayDados = array();

    if ($result) {
        if ($resultDados = mysqli_fetch_assoc($result)) {
            /*criando array com os dados do banco de dados determinando a chave que possui maior semântica. 
                  lembrando que a recepção dos dados do banco foram convertidos para um array e por isso o retorno que temos é em formato de chave considerando os nomes dados lá no banco */
            $arrayDados = array(
                "id"       => $resultDados['idContato'],
                "nome"     => $resultDados['nomeContato'],
                "telefone" => $resultDados['telefone'],
                "celular"  => $resultDados['celular'],
                "email"    => $resultDados['email'],
                "obs"      => $resultDados['obs'],
                "foto"     => $resultDados['foto'],
                "estado"   => array(
                    "idEstado" => $resultDados['idEstado'],
                    "sigla"    => $resultDados['sigla'],
                    "estado"   => $resultDados['nomeEstado']
                )
            );
        }

        //função para fechar a conexão informando qual é o banco, nesse caso armazenado na variável $conexao
        fecharConexaoMySql($conexao);

        if (isset($arrayDados)) {
            return $arrayDados;
        } else {
            return false;
        }
    }
}

//função para atualizar no banco de dados
function updateContato($dadosContato)
{
    //variável para ser utilizada no return da função. dessa maneira conseguiremos eliminar os else's da função, pois ela sempre será false a não ser que seja true
    $statusResposta = (bool) false;

    //abrir a conexão com o banco de dados, só assim poderemos fazer a inserção
    $conexao = conexaoMySql();

    //variável para armazenar o script do banco
    $sql = "update tblcontatos set
                nome     = '" . $dadosContato['nome'] . "',
                telefone = '" . $dadosContato['telefone'] . "',
                celular  = '" . $dadosContato['celular'] . "',
                email    = '" . $dadosContato['email'] . "',
                obs      = '" . $dadosContato['obs'] . "',
                foto     = '" . $dadosContato['foto'] . "',
                idEstado = '" . $dadosContato['idEstado'] . "'

            where idcontato = ".$dadosContato['id'];
           
    //executar o script no banco. _query é a função para encaminhar o script para o banco que retorna um booleano
    //primeira validação para sabermos se o script sql está correto ou não
    if (mysqli_query($conexao, $sql)) {
        //validando se houve alguma modificação; linha acrescentada no banco de dados
        if (mysqli_affected_rows($conexao)) {
            $statusResposta = true;
        } /* eis os else's que podem ser eliminados com a declaração daquela variável:
                 else {
                     $statusResposta = false;
                 }
             } else {
                 $statusResposta = false; 
             } */
    }

    fecharConexaoMySql($conexao);
    return $statusResposta;
}

//função para listar todos os contatos do banco de dados
function selectAllContatos()
{   
    //abrindo conexão com o banco
    $conexao = conexaoMySql();

    //script para listar tudo da tabela presente no banco de dados
    $sql = "select
            tblcontatos.idContato,
            tblcontatos.nome as nomeContato,
            tblcontatos.telefone,
            tblcontatos.celular,
            tblcontatos.email,
            tblcontatos.obs,
            tblcontatos.foto,
            tblestados.idEstado,
            tblestados.sigla,
            tblestados.nome as nomeEstado
        from tblcontatos inner join tblestados on tblcontatos.idEstado = tblestados.idEstado order by idcontato desc";
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
                "id"       => $resultDados['idContato'],
                "nome"     => $resultDados['nomeContato'],
                "telefone" => $resultDados['telefone'],
                "celular"  => $resultDados['celular'],
                "email"    => $resultDados['email'],
                "obs"      => $resultDados['obs'],
                "foto"     => $resultDados['foto'],
                "estado"   => array(
                    "idEstado" => $resultDados['idEstado'],
                    "sigla"    => $resultDados['sigla'],
                    "estado"   => $resultDados['nomeEstado']
                )              
            );

            $cont++;
        }

        //função para fechar a conexão informando qual é o banco, nesse caso armazenado na variável $conexao
        fecharConexaoMySql($conexao);

        if (isset($arrayDados)) {
            return $arrayDados;
        } else {
            return false;
        }
    }
}
?>