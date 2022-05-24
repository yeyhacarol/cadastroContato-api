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
    //import dos diretórios necessários que possui a função global para converter para json e que obtém a função de listar por id
    require_once('../modulo/config.php');
    require_once('../controller/controllerContatos.php');

    //resgatando o id que é passado como variável na url
    $id = $args['id'];

    //se o contato existir, agregamos a ele a função de buscar e passamos como parâmetro o id; passamos o contato buscado para a função de conversão para json e o retornamos
    if ($contatoById = buscarContato($id)) {
        //verificando se houve erro no retorno dos dados da controller
        if (!isset($contatoById['idErro'])) {
            if ($contatoJson = createJson($contatoById)) {
                return $response ->withStatus(200)
                                 ->withHeader('Content-Type', 'application/json')
                                 ->write($contatoJson);
            } 
        } else {
            $contatoJson = createJson($contatoById);

            return $response ->withStatus(404)
                             ->withHeader('Content-Type', 'application/json')
                             ->write('{"message": "Dado para requisição inválido na url.",
                                       "erro": '.$contatoJson.'}');
        }
    } else {
        return $response ->withStatus(204);
    }
});

//EndPoint: requisição para inserir um novo contato
$app->post('/contatos', function($request, $response, $args) {
    /* 201 */
});

//EndPoint: requisição para deletar contato por id
$app->delete('/contatos/{id}', function($request, $response, $args) { 
    //validando se o id é númerico, se sim resgate-o, importe os arquivos necessários, busque pelo contato a ser excluído, receba a foto, armazene os dados em um único array e por fim, delete o contato e a imagem
    if (is_numeric($args['id'])) {
        $id = $args['id'];

        require_once('../modulo/config.php');
        require_once('../controller/controllerContatos.php');

        if ($dados = buscarContato($id)) {
            $foto = $dados['foto'];

            $arrayDados = array(
                "id"   => $id,
                "foto" => $foto
            );

            $resposta = deletarContato($arrayDados);

            if (is_bool($resposta) && $resposta == true) {
                return $response ->withStatus(200)
                                 ->withHeader('Content-Type', 'application/json')
                                 ->write('{"message": "Registro excluído com sucesso."}');
            } elseif (is_array($resposta) && isset($resposta['idErro'])) {
                //verificando se o retorno da controller foi erro 5, que diz que o registro existe no banco de dados mas a imagem não existe
                if ($resposta['idErro'] == '5') {
                    return $response ->withStatus(200)
                                     ->withHeader('Content-Type', 'application/json')
                                     ->write('{"message": "Registro excluído com sucesso, porém não encontramos imagem para excluir."}');
                } else {
                    $contatoJson = createJson($resposta);
    
                    return $response ->withStatus(404)
                                     ->withHeader('Content-Type', 'application/json')
                                     ->write('{"message": "Não foi possível fazer a exclusão do registro.",
                                               "erro": '.$contatoJson.'}');
                }

            }
        } else {
            //retornando erro que diz que o cliente informou um um id inválido, ou seja, provavelmente não existe na base de dados
            return $response ->withStatus(404)
                             ->withHeader('Content-Type', 'application/json')
                             ->write('{"message": "Não foi possível encontrar registro. ID inválido."}');
        }
    } else {
        //retornando erro que diz que o cliente informou um id errado(alfabético)
        return $response ->withStatus(404)
                         ->withHeader('Content-Type', 'application/json')
                         ->write('{"message": "Obrigatório informar um ID válido(numérico)."}');
    }
});

//linha que excuta todos os endpoints
$app->run();
?>