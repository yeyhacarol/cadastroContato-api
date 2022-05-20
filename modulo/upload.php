<?php
/* objetivo: arquivo para fazermos upload de imagem; autora: Carolina Silva; versão: 1.0; data de criação: 25/04/2022 */

/* função para realizar upload de imagem */
function uploadFile($arrayFile)
{
    require_once('modulo/config.php');

    $arquivo = $arrayFile;
    $sizeFile = (int) 0;
    $typeFile = (string) null;
    $nameFile = (string) null;
    $tempFile = (string) null;


    /* validando se o arquivo é maior que zero e possui extensão, ou seja, válido */
    if ($arquivo['size'] > 0 && $arquivo['type'] != "") {
        /* convertendo de byte para kb. para subir as unidades de medidas basta dividir por 1024*/
        $sizeFile = $arquivo['size'] / 1024;

        /* recuperando o tipo do arquivo */
        $typeFile = $arquivo['type'];

        /* recuperando o nome do arquivo */
        $nameFile = $arquivo['name'];

        /* recuperando caminho do diretório onde os arquivos estão temporariamente */
        $tempFile = $arquivo['tmp_name'];

        /* validando para permitir o upload apenas arquivos de no máximo 5mb */
        if ($sizeFile <= MAX_SIZE_FILE_UPLOAD) {
            /* segundo if pra permitir apenas extensões válidas */
            if (in_array($typeFile, FILE_EXT_UPLOAD)) {
                /* separando o nome do arquivo da sua extensão */
                $nome = pathinfo($nameFile, PATHINFO_FILENAME);

                /* separando somente a extenão do arquivo sem o nome */
                $extensao = pathinfo($nameFile, PATHINFO_EXTENSION);

                /* há uma série de algoritmos para criptografia dos dados. os listados são os nativos do php */
                // md5    - mais simples
                // sha1() - aumenta a qtde de caracteres
                // hash() - permite maior manipulação, aumentando ainda mais o número de caracteres

                /* uniqid() gera uma sequência numérica que depende de diversos fatores como configurações da máquina 
                    e ainda adiciona, time(), a hora/minuto/segundo a essa sequência númerica */
                $nomeCriptografado = md5($nome . uniqid(time()));

                /* recuperando nome do arquivo modificado e concatenando com a extensão */
                $foto = $nomeCriptografado . "." . $extensao;

                /* envia o arquivo da pasta temporária do apache para a pasta criada no projeto */
                if (move_uploaded_file($tempFile, FILE_DIRECTORY_UPLOAD . $foto)) {
                    return $foto;
                } else {
                    return array(
                        'idErro'  => 13,
                        'message' => 'Não foi possível fazer upload no arquivo no servidor.'
                    );
                }
            } else {
                return array(
                    'idErro'  => 12,
                    'message' => 'Extensão do arquivo inválida para upload.'
                );
            }
        } else {
            return array(
                'idErro'  => 10,
                'message' => 'Arquivo grande demais para upload.'
            );
        }
    } else {
        return array(
            'idErro'  => 11,
            'message' => 'Não foi possível fazer upload de imagem. Arquivo não selecionado.'
        );
    }
}
