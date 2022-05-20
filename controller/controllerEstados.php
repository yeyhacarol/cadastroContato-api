<?php 
/* Objetivo: arquivo responsável pela manipulação de dados de estados. Este arquivo fará a ponte entre a view e a model; 
    autora: Carolina Silva; data de criação: 10/05/2022; versão: 1.0  */

    require_once('modulo/config.php');

    //função para solicitar os dados da model e encaminhar a lista de estados para a view 
    function listarEstado() {
        //import do arquivo que busca dados no banco
        require_once('model/bd/estado.php');

        //chamando a função que busca os dados no banco e armazenando-a em uma variável para uso posterior
        $dados = selectAllEstados();
        if(!empty($dados)) {
            return $dados;
        } else {
            return false;
        }
}

?>