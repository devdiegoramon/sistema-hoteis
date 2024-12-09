<?php

if (isset($_POST['btn-cadastro'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $nivel = $_POST['nivel'];
    $nome = $_POST['nome'];
    $dtnascimento = dataAmerica($_POST['dtnasc']);
    $cpf = $_POST['cpf'];

    $datag = date("Y-m-d");
    $horag = date("H:i:s");

    $senha = $hashed_password = password_hash($senha, PASSWORD_DEFAULT); //GERA UM HASH PARA ENCRIPTAR A SENHA

    $sqlConfere = "SELECT 1 FROM funcionario WHERE login = '{$login}'";
    $resultConfere = mysqli_query($con, $sqlConfere);

    $sqlConfereCpf = "SELECT 1 FROM funcionario WHERE cpf = '{$cpf}'";
    $resultConfereCpf = mysqli_query($con, $sqlConfereCpf);
    if (mysqli_num_rows($resultConfereCpf) > 0) {
        $text = "Cadastro não realizado: Funcionário já cacastrado anteriormente";
        $type = 1;
    } elseif (mysqli_num_rows($resultConfere) > 0) {
        $text = "Cadastro não realizado: Login em uso";
        $type = 1;
    } else {

        $sqlCadastro = "INSERT INTO funcionario VALUES (null,
                                                        '{$nome}',
                                                        '{$login}',
                                                        '{$senha}',
                                                        '{$dtnascimento}',
                                                        '{$cpf}',
                                                        '{$nivel}',
                                                        's')";
        if (mysqli_query($con, $sqlCadastro)) {
            //GERA LOG
            $idNovoFuncionario = mysqli_insert_id($con);
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou o funcionario <b>Nº$idNovoFuncionario</b> com login de: <b>$login</b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                               {$idFuncionario},
                                               'cadastro',
                                               '{$descricaoLog}',
                                               'funcionario',
                                               '{$idNovoFuncionario}',
                                               null,
                                               '$date',
                                               '$hora')";

            $text = "Funcionário cadastrado com sucesso";
            $type = 0;
        } else {
            $text = "Erro ao cadastrar funcionário";
            $type = 1;
        }
    }
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    $type = 1;
    header("Location: ../../../index.php?text=$text&type=$type");
}