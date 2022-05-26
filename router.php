<?php
/* Objetivo: arquivo de rota genérico para receber todas as requisições da view(dados de um form, listagem de dados, ação de excluir ou atualizar) 
e enviar/receber para(da) a controller; autora: Carolina Silva; data criação: 04/03/2022; última modificação: 26/04/2022; versão: 1.0  */


$action = (string) null;
$component = (string) null;

//inicialmente devemos identificar através de qual method é a requisição do form, nesse caso ambas
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {

    //recebendo dados via URL para saber quem está solicitando e qual ação será realizada
    $component = strtoupper($_GET['component']);
    $action = strtoupper($_GET['action']);

    //estrutura condicional para identificarmos quem está fazendo a requisição para a router
    switch ($component) {
        case 'CONTATOS':
            //importando a controller
            require_once('./controller/controllerContatos.php');

            //verificando o tipo de ação requerida
            if ($action == 'INSERIR') {

                //validando para tratar se imagem existe na chegada dos dados do html
                if (isset($_FILES) && !empty($_FILES)) {

                    //array que obtém todos os dados vindos do html e da api
                    $arrayDados = array(
                        $_POST, 
                        "file" => $_FILES
                    );

                    //chamar a função de inserir da controller
                    $promessa = inserirContato($arrayDados);
                } else {
                    $arrayDados = array(
                        $_POST, 
                        "file" => null
                    );

                    $promessa = inserirContato($arrayDados);
                }
                /*verificando o tipo de dado retornado. se for um booleano, verificará se é verdadeiro e emitirá uma mensagem de sucesso,
                  caso contrário, verificará se é um array nesse caso emitirá uma mensagem de erro */
                if(is_bool($promessa)) {
                    if($promessa) {
                        echo("<script>
                            alert('Registro inserido com sucesso!') 
                            window.location.href = 'index.php'; //para que o alert após fechado retorne para a página ao invés de ficar numa página branca 
                        </script>");
                    }  
                } elseif (is_array($promessa)) {
                    echo("<script>
                            alert('".$promessa['message']."') 
                            window.history.back(); //para que quando retorne a página anterior, os dados inseridos ainda estejam nos campos 
                        </script>");
                }
            } elseif ($action == 'DELETAR') {
                //recebendo via get(url) o id do registro que deve ser excluído, atráves do link da imagem que foi acionado na index
                $idContato = $_GET['id'];
                $foto = $_GET['foto'];

                /* criando array para encaminhar os valores e da foto juntos para a controller, dessa maneira não precisamos ajustar as características da função */
                $arrayDados = array(
                    "id"   => $idContato,
                    "foto" => $foto
                );
                
                //chamando acão de deletar da controller
                $promessa = deletarContato($arrayDados);

                if(is_bool($promessa)) {

                    if($promessa) {
                        echo("<script>
                            alert('Registro excluído com sucesso!') 
                            window.location.href = 'index.php'; 
                        </script>"); 
                    } 
                } elseif (is_array($promessa)) {
                    echo("<script>
                            alert('".$promessa['message']."') 
                            window.history.back(); 
                        </script>");
                }
            } elseif ($action == 'BUSCAR') {
                //recendo via GET o id do contato que deve ser buscado para o editar posteriormente
                $idContato = $_GET['id'];

                //chamando a função de buscar contato da contoller
                $dados = buscarContato($idContato);

                /*habilitando função de variável de sessão, pois quando acessamos a action de buscar, a index nos leva a router e 
                    acabamos por perder nossas variáveis por isso é necessário armazená-las no storage do navegador pra que elas sejam
                    perdidas apenas quando o navegador for desligado. dessa maneira podemos inseri-las nas caixas de texto
                */
                session_start();

                //variável do tipo SESSION nomeada por DADOSCONTATO que recebe a variável de DADOS que contém os dados que o banco de dados retornou para a busca do id
                //tal variável será utilizada na index.php para colocarmos os dados no form para visualização e posteriormente edição!
                $_SESSION['dadosContato'] = $dados;

                //para que a tela de router apenas se renderize e continue sendo a mesma do form piscando apenas uma vez, quase imperceptível visualmente
                require_once('index.php');

                //caso queiramos chamar a index e realmente trocar de tela, utilizamos:
                // header('location: index.php');

            } elseif ($action == 'EDITAR') {
                /* resgatando o id e a foto e encaminhado pelo action do form através da url */
                $idContato = $_GET['id'];
                $foto = $_GET['foto'];

                /* criando array com ambos os dados para podermos mandar como parâmetro da função, sem precisarmos alterá-la colocando 3 parâmetros */
                $arrayDados = array(
                    "id"   => $idContato,
                    "foto" => $foto,
                    "file" => $_FILES 
                );

                $promessa = atualizarContato($_POST, $arrayDados);
                /*verificando o tipo de dado retornado. se for um booleano, verificará se é verdadeiro e emitirá uma mensagem de sucesso,
                  caso contrário, verificará se é um array nesse caso emitirá uma mensagem de erro */
                if(is_bool($promessa)) {
                    if($promessa) {
                        echo("<script>
                            alert('Registro atualizado com sucesso!') 
                            window.location.href = 'index.php'; 
                        </script>");
                    }  
                } elseif (is_array($promessa)) {
                    echo("<script>
                            alert('".$promessa['message']."') 
                            window.history.back(); 
                        </script>");
                }
            }

            break;
    }
}

?>