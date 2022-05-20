<?php
/* objetivo: arquivo para armazenar as varíaveis e as constantes, e as funções globais do projeto; autora: Carolina Silva; versão: 1.0; data de criação: 25/04/2022 */

//Variáveis e constantes globais
/*  limitação de 5mb para upload de imagens */
const MAX_SIZE_FILE_UPLOAD = 5120;

/* definindo as extensões permitidas de arquivo */
const FILE_EXT_UPLOAD = array("image/jpg", "image/png", "image/jpeg", "image/gif");

/* caminho da pasta de upload de arquivos definitivo */
const FILE_DIRECTORY_UPLOAD = "arquivos/";

//retorna o caminho absoluto físico do servidor  
define('SRC', $_SERVER['DOCUMENT_ROOT'].'/carol/aula07');

//Funções globais do projeto
/* função para converter um array em um formato json */
function createJson($arrayDados) {
    //validando array sem dados
    if (!empty($arrayDados)) {
    //configurar o padrão da conversão para formato json
    header('Content-Type: application/json');

    $dadosJson = json_encode($arrayDados); 

    return $dadosJson;

    } else 
        return false;
    
}

?>
