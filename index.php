<?php
/* import do arquivo de configurações do projeto */
require_once('modulo/config.php');

/* criando variável que armazena o caminho padrão na primeira vez que entrarmos no site. onde diferenciamos
 qual a action que deve ser executada e enviada para router */
$form = (string) "router.php?component=contatos&action=inserir";

/* variavél para carregar o nome da foto do banco de dados */
$foto = (string) null;
//inicializando as variáveis como nulas para não aparecer o erro nos campos
/* $nome está tendo seu erro omitido com @ no html */
/* $telefone esá sendo verificado com if ternário no html*/
$celular = null;
$email = null;
$obs = null;

//variável para ser utilizada no carregar dos estados na opção de editar
$idEstado = (string) null;

/* resgatando dados da variável de sessão para inserir nos inputs */

//verificando se a variável de sessão está ativada no servidor
if (session_status()) {
    //valida se a variável de sessão possui conteúdo, se sim resgata os dados necessários para colocar na caixa de texto
    if (!empty($_SESSION['dadosContato'])) {
        $id       = $_SESSION['dadosContato']['id'];
        $nome     = $_SESSION['dadosContato']['nome'];
        $telefone = $_SESSION['dadosContato']['telefone'];
        $celular  = $_SESSION['dadosContato']['celular'];
        $email    = $_SESSION['dadosContato']['email'];
        $obs      = $_SESSION['dadosContato']['obs'];
        $foto     = $_SESSION['dadosContato']['foto'];
        $idEstado = $_SESSION['dadosContato']['idEstado'];

        /* quando o botão de editar for acionado e os dados forem armazenados na session, a url será modificada do qual a action será de editar.
         concatenando com o id para sabermos qual o contato a ser editadoS*/
        $form = "router.php?component=contatos&action=editar&id=" . $id . "&foto=" . $foto;

        /* destruindo variável da memória do servidor */
        unset($_SESSION['dadosContato']);
    }
}

?>

<!DOCTYPE>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title> Cadastro </title>
    <!-- to change the head icon -->
    <link rel="icon" type="image/x-icon" href="https://as1.ftcdn.net/v2/jpg/01/72/22/52/1000_F_172225270_QtBzpfx2g0I78X1XZ2VxL5FgTeAGt4p9.jpg">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

    <div id="cadastro">
        <div id="cadastroTitulo">
            <h1> Cadastro de Contatos </h1>
        </div>

        <div id="cadastroInformacoes">
            <!-- enctype="multipart/form-data" é OBRIGATÓRIO para enviar arquivos do form em html para o servidor -->
            <form action="<?= $form ?>" name="frmCadastro" method="post" enctype="multipart/form-data">
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Nome: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="text" name="txtNome" value="<?= @$nome ?>" placeholder="Digite seu Nome" maxlength="100">
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Estado: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <select name="sltEstado">
                            <option value="">Selecione um estado</option>
                            <?php
                            require_once('controller/controllerEstados.php');

                            $listEstados = listarEstado();

                            foreach ($listEstados as $dados) {
                            ?>
                                <option <?= $idEstado == $dados['idEstado'] ? 'selected' : null ?> value="<?= $dados['idEstado'] ?>"><?= $dados['nome'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Telefone: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="tel" name="txtTelefone" value="<?= isset($telefone) ? $telefone : null ?>">
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Celular: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="tel" name="txtCelular" value="<?= $celular ?>">
                    </div>
                </div>

                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Email: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="email" name="txtEmail" value="<?= $email ?>">
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Escolha um arquivo: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="file" name="fleFoto" accept=".jpg, .png, .jpeg">
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Observações: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <textarea name="txtObs" cols="50" rows="7"><?= $obs ?></textarea>
                    </div>
                </div>
                <div class="campos">
                    <img src="<?= FILE_DIRECTORY_UPLOAD . $foto ?>">
                </div>


                <div class="enviar">
                    <div class="enviar">
                        <input type="submit" name="btnEnviar" value="Salvar">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="consultaDeDados">
        <table id="tblConsulta">
            <tr>
                <td id="tblTitulo" colspan="6">
                    <h1> Consulta de Dados.</h1>
                </td>
            </tr>
            <tr id="tblLinhas">
                <td class="tblColunas destaque"> Nome </td>
                <td class="tblColunas destaque"> Celular </td>
                <td class="tblColunas destaque"> Email </td>
                <td class="tblColunas destaque"> Opções </td>
                <td class="tblColunas destaque"> Foto </td>
            </tr>

            <?php
            //import d arquivo par asoliciar a função
            require_once('controller/controllerContatos.php');
            //função responsável por listar os conatatos
            if ($listContato = listarContato()) {
                //foreach para percorrermos e printarmos os dados de contato um a um

                foreach ($listContato as $dados) {
                    /* igualando variável com o valor que vem do banco de dados, ou seja, o nome da foto */
                    $foto = $dados['foto'];

            ?>
                    <tr id="tblLinhas">
                        <td class="tblColunas registros"><?= $dados['nome'] ?></td>
                        <td class="tblColunas registros"><?= $dados['celular'] ?></td>
                        <td class="tblColunas registros"><?= $dados['email'] ?></td>
                        <td class="tblColunas registros"><img src="<?= FILE_DIRECTORY_UPLOAD . $foto ?>" class="foto" alt=""></td>

                        <td class="tblColunas registros">
                            <a href="router.php?component=contatos&action=buscar&id=<?= $dados['id'] ?>">
                                <img src="img/edit.png" alt="Editar" title="Editar" class="editar">
                            </a>
                            <!-- href que direciona a página para este determinado caminho. onclick para adicionar js diretamente no html; confirm para uma mensagem 
                        sim/não; return é como uma espera pela resposta do confirm, se é true ou false -->
                            <a onclick="return confirm('Deseja mesmo exluir o contato <?= $dados['nome'] ?>?');" href="router.php?component=contatos&action=deletar&id=<?= $dados['id'] ?>&foto=<?= $foto ?>">
                                <img src="img/trash.png" alt="Excluir" title="Excluir" class="excluir">
                            </a>
                            <img src="img/search.png" alt="Visualizar" title="Visualizar" class="pesquisar">
                        </td>
                    </tr>
            <?php

                }
            }

            ?>
        </table>
    </div>
</body>

</html>