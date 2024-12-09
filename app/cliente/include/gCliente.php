<?php

//VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $dtnasc = dataAmerica($_POST['dtnasc']);
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];

    if (empty(trim($nome)) or empty(trim($email))) {
        $text = "Por favor, preencha todos os campos.";
        $type = 2;
        header("Location: ../index.php?text=$text&type=$type");
        return;
    }

    $sqlConfereCpf = "SELECT 1 FROM cliente WHERE cpf = '{$cpf}'"; //CONFERE SE O CLIENTE JÁ ESTÁ
    $resultConfereCpf = mysqli_query($con, $sqlConfereCpf);
    $sqlConfereEmail = "SELECT 1 FROM cliente WHERE email = '{$email}'";
    $resultConfereEmail = mysqli_query($con, $sqlConfereEmail);

    $dataIdade = new DateTime($dtnasc);
    $idade = $dataIdade->diff(new DateTime(date('Y-m-d')));
    $idade = $idade->format('%Y'); //IDADE DO CLIENTE

    if ($idade >= 18) {
        //VERIFICA SE NOME E NUMERO JÁ ESTÃO CADASTRADOS
        if ((mysqli_num_rows($resultConfereCpf) > 0) and (mysqli_num_rows($resultConfereEmail) > 0)) {
            $text = "Cadastro não realizado: CPF e endereço de e-mail já cadastrado anteriormente";
            $type = 2;
        } elseif (mysqli_num_rows($resultConfereCpf) > 0) {
            $text = "Cadastro não realizado: CPF já cadastrado anteriormente";
            $type = 2;
        } elseif (mysqli_num_rows($resultConfereEmail) > 0) {
            $text = "Cadastro não realizado: Endereço de e-mail já cadastrado anteriormente";
            $type = 2;
        } else {
            //REALIZA CADASTRO NO BANCO DE DADOS
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $sqlCadastro = "INSERT INTO cliente VALUES (null,
                                                        '{$nome}',
                                                        '{$cpf}',
                                                        '{$dtnasc}',
                                                        '{$email}',
                                                        '{$telefone}',
                                                        '{$estado}',
                                                        '{$cidade}',
                                                        '{$date}',         
                                                        's')";
            if (mysqli_query($con, $sqlCadastro)) {
                $idCliente = mysqli_insert_id($con);
                //GERA LOG
                $idFuncionario = $_SESSION['idlogin'];
                $funcionario = $_SESSION['login'];
                $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou o cliente <b>Nº$idCliente</b> com nome: <b>$nome</b>, no dia <b>$data</b> às <b>$hora</b>";
                $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'cadastro',
                                              '{$descricaoLog}',
                                              'cliente',
                                              {$idFuncionario},
                                              null,
                                              '$date',
                                              '$hora')";
                if (mysqli_query($con, $sqlLog)) {
                    
                }
                //FIM LOG
                $text = "Cadastro realizado com sucesso";
                $type = 0;
            } else {
                $text = "Cadastro não realizado: Erro ao cadastrar";
                $type = 1;
            }
        }
    } else {
        $text = "Cadastro não realizado: Necessário ter 18 anos ou mais para continuar";
        $type = 2;
    }
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}