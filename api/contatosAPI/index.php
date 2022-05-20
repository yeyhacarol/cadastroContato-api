<?php 

//arquivo que ativa os serviços do slim que fará as instâncias so slim
require_once('vendor\autoload.php');

//criando um objeto do slim chamado app para configurarmos os endpoints
$app = new \Slim\App();

//EndPoint: requisição para listar todos os contatos. é assim que criamos os endpoints
$app->get('/contatos', function($request, $response, $args) {
    require_once('../modulo/config.php');
    require_once('../controller/controllerContatos.php');

    //solitação dos dados da controller
    if ($listaContatos = listarContato()) {
        //conversão do array em dados em json
        if ($listaJson = createJson($listaContatos)) {
            //caso haja dados a serem retornados o status code retornado será o 200 (ok) e enviaremos um json com todos os dados encontrados
            return $response ->withStatus(200)
                             ->withHeader('Content-Type', 'application/json') 
                             ->write($listaJson);
          
        }
    } else {
        //retorna um status code (404) que diz que a requisição foi aceita, mas não possui nada a retornar
        return $response ->withStatus(404)
                         ->withHeader('Content-Type', 'application/json') 
                         ->write('{"message": "Item não encontrado."}');
    }

    
});

//EndPoint: requisição para listar contatos pelo id
$app->get('/contatos/{id}', function($request, $response, $args) {
    require_once('../modulo/config.php');
    require_once('../controller/controllerContatos.php');

    $id = $args['id'];

    if ($contatoById = buscarContato($id)) {
        if ($contatoJson = createJson($contatoById)) {
            return $response ->withStatus(200)
                             ->withHeader('Content-Type', 'application/json')
                             ->write($contatoJson);
        } else {
            return $response ->withStatus(204);
        }
    }


});

//EndPoint: requisição para inserir um novo contato
$app->post('/contatos', function($request, $response, $args) {
    /* 201 */
});

//linha que excuta todos os endpoints
$app->run();
?>