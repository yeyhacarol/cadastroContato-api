<?php
   /*  Objetivo: arquivo responsável pela manipulação de dados de contatos. Este arquivo fará a ponte entre a view e a model; 
    autora: Carolina Silva; data de criação: 04/03/2022; última modificação: 26/04/2022; versão: 1.0 */ 

    require_once(SRC.'modulo/config.php');

    //função para receber os dados da view e encaminhar para a model (inserir)
    function inserirContato($dadosContato) {
        /* declarando variável e iniciando como nula para o caso de não haver upload */
        $nomeFoto = (string) null;

        //verificando se o objeto $dadosContato não está vazio
        if (!empty($dadosContato)) {
            //armazenando numa variável o objeto imagem que foi encaminhado dentro do array
            $file = $dadosContato['file'];

            //validando se as caixas de texto de nome, celular email e estado não estão vazias, pois o preenchimento é obrigatório no banco de dados
            if (!empty($dadosContato[0]['nome']) && !empty($dadosContato[0]['celular']) && !empty($dadosContato[0]['email']) && !empty($dadosContato[0]['estado'])) {
 
                /* validando se chegou algum arquivo para upload */
                if ($file['foto']['name'] != null) {

                    /* import do arquivo que contém a função de upload */       
                    require_once(SRC.'modulo/upload.php');

                    /* chamando a função de upload */
                    $nomeFoto = uploadFile($file['foto']);

                    /* verificando se tudo ocorreu como esperado ou não */
                    if (is_array($nomeFoto)) {
                        /* no caso de erros no processo de upload, a função retornará um array com a mensagem de erro. tal array será retornado para a router e ela exibirá a mensagem para o usuário */
                        return $nomeFoto;
                    } 
                                      
                }

                /*criação do array que contém dados que serão encaminhados para a model para inserção deles no bd.
                 é importante criar o array conforme as necessidades do bd e de acordo com a nomenclatura utilizada nele*/
                $arrayDados = array (
                    "nome"     => $dadosContato[0]['nome'],
                    "telefone" => $dadosContato[0]['telefone'],
                    "celular"  => $dadosContato[0]['celular'],  
                    "email"    => $dadosContato[0]['email'],
                    "obs"      => $dadosContato[0]['obs'],
                    "foto"     => $nomeFoto,
                    "idEstado"   => $dadosContato[0]['estado']
                );
    
                //importar arquivo de manipulação de dados do bd
                require_once(SRC.'model/bd/contato.php');
                //função presente na model
                if(insertContato($arrayDados)) {
                    return true;
                } else {
                    return array('idErro'  => 1,
                                 'message' => 'Não foi possível inserir dados no banco');
                }
            } else {
                return array('idErro'  => 2,
                             'message' => 'Há campos obrigatórios não preenchidos');
            } 
        }
    }

    //função que procurará no banco o contato que deverá ser editado
    function buscarContato($id) {
        if ($id != 0 && !empty($id) && is_numeric($id)) {
            require_once(SRC.'model/bd/contato.php');

            //solicitando a função da model(contato.php) que vai buscar os dados no banco
            $dados = selectByIdContato($id);

            //validando se existem dados a serem devolvidos
            if (!empty($dados)) {
                return $dados;
            } else {
                return false;
            }
        } else {
            return array('idErro'  => 4,
                         'message' => 'Não foi possível buscar registro. ID inválido.');
        }
    }

    //função para receber os dados da view e encaminhar para a model (atualizar)
    function atualizarContato($dadosContato, $arrayDados) {
        $statusUpload = (bool) false;
        /* recebe id, a foto(nome da foto que já existe) enviada pelo arrayDados */
        $id = $arrayDados['id'];
        $foto = $arrayDados['foto'];
        //objeto de array referente a nova foto que poderá ser enviada ao servidor
        $file = $arrayDados['file'];

        if (!empty($dadosContato)) {
            //validando se as caixas de texto de nome e celular não estão vazias, pois o preenchimento é obrigatório no banco de dados
            if (!empty($dadosContato['nome']) && !empty($dadosContato['celular']) && !empty($dadosContato['email'])) {
                /* validando o id para garantir que ele seja válido */
                if($id != 0 && !empty($id) && is_numeric($id)) {
                    /* verificando se o arquivo existe. verifica se será enviada uma nova foto ao servidor */
                    if ($file['foto']['name'] != null) {
                        /* import do arquivo que contém a função de upload */
                        require_once('modulo/upload.php');
                        /* chamando a função para atualizar o arquivo que recebe como parâmetro o arquivo */
                        $novaFoto = uploadFile($file['fleFoto']);

                        $statusUpload = true;
                    } else {
                        /* permanece a mesmo foto no banco de dados */
                        $novaFoto = $foto;
                    }

                    /*criação do array que contém dados que serão encaminhados para a model para inserção deles no bd.
                     é importante criar o array conforme as necessidades do bd e de acordo com a nomenclatura utilizada nele*/
                    $arrayDados = array (
                        "id"       => $id,
                        "nome"     => $dadosContato['nome'],
                        "telefone" => $dadosContato['telefone'],
                        "celular"  => $dadosContato['celular'],
                        "email"    => $dadosContato['email'],
                        "obs"      => $dadosContato['obs'],
                        "foto"     => $novaFoto,
                        "idEstado" => $dadosContato['estado']
                    );
        
                    //importar arquivo de manipulação de dados do bd; import do arquivo de configuração 
                    require_once('model/bd/contato.php');
                    require_once('modulo/config.php');
                    
                    //função presente na model
                    if(updateContato($arrayDados)) {
                        //validando se será necessário apagar a foto antiga. ativada em true na linha 101, quando realizamos o upload de uma nova foto para o servidor
                        if ($statusUpload) {
                            //apaga a foto antiga do servidor
                            unlink(FILE_DIRECTORY_UPLOAD.$foto);
                        }
                        return true;
                    } else {
                        return array('idErro'  => 1,
                                     'message' => 'Não foi possível editar dados no banco.');
                    }
                } else {
                    return array('idErro'  => 4,
                                 'message' => 'Não foi possível editar registro. ID inválido ou não inserido.');
                } 
            } else {
                return array('idErro'  => 2,
                             'message' => 'Algum campo obrigatório não preenchido.');
            }
        }
    }

    //função para realizar a exclusão de um contato (excluir)
    function deletarContato($arrayDados) {
        /* recebendo o id e a foto do registro que será excluido, no caso a foto será excluída da pasta do servidor */
        $id = $arrayDados['id'];
        $foto = $arrayDados['foto'];

        //verificando se o id é válido; diferente de zero, existente e númerico respectivamente
        if($id != 0 && !empty($id) && is_numeric($id)) {
            //import da model
            require_once(SRC.'model/bd/contato.php');

            //chamando a função da model e verificando se o retorno foi true/false e exibindo mensagens em caso de erro
            if(deleteContato($id)) {
                /* validando caso a imagem não exista com o registro */
                if ($foto != null) {
                    /* função para deletar arquivos de um diretório, do php. aqui apagamos a foto fisicamente do diretório no servidor */
                    if(@unlink(SRC.FILE_DIRECTORY_UPLOAD.$foto)) {
                        return true;
                    } else {
                        return array('idErro'  => 5,
                                     'message' => 'O banco conseguiu deletar registro, mas a imagem não foi excluída do diretório no servidor.');
                    }
                } else {
                    return true;
                }
            } else {
                return array('idErro'  => 3,
                             'message' => 'O banco não conseguiu deletar registro.');
            }
        } else {
            return array('idErro'  => 4,
                         'message' => 'Não foi possível excluir registro. ID inválido ou não inserido.');
        }
    }

    //função para solicitar os dados da model e encaminhar a lista de contatos para a view (inserir)
    function listarContato() {
            //import do arquivo que busca dados no banco
            require_once(SRC.'model/bd/contato.php');

            //chamando a função que busca os dados no banco e armazenando-a em uma variável para uso posterior
            $dados = selectAllContatos();
            if(!empty($dados)) {
                return $dados;
            } else {
                return false;
            }
    }


?>