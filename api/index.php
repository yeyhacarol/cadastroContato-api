<?php 
    /* Objetivo: arquivo principal da api que irá receber a url requisitada e redirecionar para as api';
    *  autora: Carolina Silva; data de criacão: 19/05/2022; versão: 1.0 
    */


    //para especificar a origem da requisição e quais serão permitidas, nesse caso estamos permitindo qualquer permissão http, ou seja, qualquer página web poderá fazer requisição a esta api
    header('Access-Control-Allow-Origin: *');
    //para especificar os métodos que serão utilizados, nesse caso para o crud (create(post), read(get), update(put), delete(delete)). Os 4 verbos http
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    // permite ativar o content-type das requisições, ou seja, o formato de dados que será utilizado (json, xml, form/data etc)
    header('Access-Control-Allow-Header: Content-Type');
    // para especificar os content-types que serão utilizados, nesse caso apenas o padrão json
    header('Content-Type: application/json');

    // recebe a url digitada na requisição
    $urlHTTP = (string) $_GET['url'];

    //explode() converte string em array levando em consideração um caractere especial
    $url = explode('/', $urlHTTP);

    //verificar qual api será encaminhada para requisição
    switch (strtoupper($url[0])) {
        case 'CONTATOS':
            
            require_once('contatosAPI/index.php');

            break;
        
    }


?>