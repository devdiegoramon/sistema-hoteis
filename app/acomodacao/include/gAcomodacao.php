<?php

//VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";

    $nome = $_POST['nome'];
    $numero = $_POST['numero'];
    $tipo = $_POST['tipo'];
    $valor = !empty($_POST['valor']) ? $_POST['valor'] : 'null';
    $capacidade = $_POST['capacidade'];
    $descricao = $_POST['descricao'];
    $vaga = $_POST['vaga'];

    if (empty(trim($nome)) or empty(trim($numero)) or empty(trim($capacidade))) {
        $text = "Por favor, preencha todos os campos.";
        $type = 2;
        header("Location: ../index.php?text=$text&type=$type");
        return;
    }

    if (empty($descricao)) {
        $descricao = "Sem descrição";
    }

    $cor = $_POST['cor'];
    $valor = str_replace(".", "", $valor); //FORMATA O VALOR PARA CADASTRAR NO BANCO
    $valor = str_replace(",", ".", $valor);

    $sqlConfereNome = "SELECT 1 FROM acomodacao WHERE nome = '{$nome}'";
    $resultConfereNome = mysqli_query($con, $sqlConfereNome);
    $sqlConfereNumero = "SELECT 1 FROM acomodacao WHERE numero = '{$numero}'";
    $resultConfereNumero = mysqli_query($con, $sqlConfereNumero);

    //VERIFICA SE NOME E NUMERO JÁ ESTÃO CADASTRADOS
    if ((mysqli_num_rows($resultConfereNome) > 0) and (mysqli_num_rows($resultConfereNumero) > 0)) {
        echo $text = "Cadastro não realizado: Nome e número já cadastrado anteriormente";
        $type = 2;
    } elseif (mysqli_num_rows($resultConfereNome) > 0) {
        $text = "Cadastro não realizado: Nome já cadastrado anteriormente";
        $type = 2;
    } elseif (mysqli_num_rows($resultConfereNumero) > 0) {
        $text = "Cadastro não realizado: Número já cadastrado anteriormente";
        $type = 2;
    } else {

        //REALIZA CADASTRO NO BANCO DE DADOS
        $date = date("Y-m-d"); //FORMATO AMERICANO
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $sqlCadastro = "INSERT INTO acomodacao VALUES (null,
                                                      {$tipo},
                                                      '{$nome}',
                                                      {$numero},
                                                      {$valor},
                                                      {$capacidade},
                                                      '{$descricao}',
                                                      's',
                                                      '{$date}',
                                                      '{$hora}',
                                                      '{$cor}')";
        if (mysqli_query($con, $sqlCadastro)) {
            $idacomodacao = mysqli_insert_id($con);

            //VERIFICA SE ALGUMA VAGA DE ESTACIONAMENTO JÁ ESTÁ CADASTRADA
            $textVaga = '';
            foreach ($vaga as $dadosvaga) {
                $dadosvaga = explode('-', $dadosvaga);
                $idvaga = $dadosvaga[0];
                $numvaga = $dadosvaga[1];

                $sqlVaga = "UPDATE estacionamento
                            SET idacomodacao = {$idacomodacao}
                            WHERE idvaga = {$idvaga}";
                if (mysqli_query($con, $sqlVaga)) {

                    //GERA LOG
                    $idFuncionario = $_SESSION['idlogin'];
                    $funcionario = $_SESSION['login'];
                    $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou a vaga de estacionamento de número <b>$numvaga</b> para a acomodação $nome, no dia <b>$data</b> às <b>$hora</b>";
                    $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'cadastro',
                                              '{$descricaoLog}',
                                              'estacionamento',
                                              {$idestacionamento},
                                              null,
                                              '$date',
                                              '$hora')";
                    if (mysqli_query($con, $sqlLog)) {

                    }
                    //FIM LOG
                }
            }

            //GERA LOG CADASTRO DE ACOMODACAO
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou uma acomodação com nome: <b>$nome</b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'deletar',
                                              '{$descricaoLog}',
                                              'estacionamento',
                                              '{$idacomodacao}',
                                              null,
                                              '$date',
                                              '$hora')";
            if (mysqli_query($con, $sqlLog)) {

            }
            //FIM LOG
            $text = "Cadastro realizado com sucesso";
            $type = 0;
        } else {
            $text = "Erro ao cadastrar a acomodação: Entre em contato com o administrador do sistema";
            $type = 1;
        }
    }
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}