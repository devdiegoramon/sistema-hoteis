<?php

include "../../config/config.php";

if (isset($_POST['editar'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";

    $idfuncionario = $_POST['idfuncionario'];
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $cpf = $_POST['cpf'];
    $nivel = $_POST['nivel'];
    $dtnascimento = dataAmerica($_POST['dtnasc']);

    if (isset($_POST['senha'])) {
        $senha = $_POST['senha'];
        $senha = $hashed_password = password_hash($senha, PASSWORD_DEFAULT); //GERA UM HASH PARA ENCRIPTAR A SENHA

        $sqlSenha = "UPDATE funcionario SET senha = '{$senha}'
                      WHERE idlogin = {$idfuncionario}";

        if (mysqli_query($con, $sqlSenha)) {
            //GERA LOG
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $descricaoLog = "Funcionário: <b>$funcionario</b>, alterou a senha do funcionario <b>$login</b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                               {$idFuncionario},
                                               'troca de senha',
                                               '{$descricaoLog}',
                                               'funcionario',
                                               '{$idNovoFuncionario}',
                                               null,
                                               '$date',
                                               '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
        }
    }

    $sqlConfere = "SELECT 1 FROM funcionario WHERE login = '{$login}' AND idlogin != {$idfuncionario}";
    $resultConfere = mysqli_query($con, $sqlConfere);
    if (mysqli_num_rows($resultConfere) == 0) {

         $sql = "UPDATE funcionario SET nome = '{$nome}',
                                       login = '{$login}',
                                       cpf = '{$cpf}',
                                       nivel = '{$nivel}',
                                       dtnascimento = date('{$dtnascimento}')
                    WHERE idlogin = {$idfuncionario}";
        if (mysqli_query($con, $sql)) {

            //GERA LOG
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $descricaoLog = "Funcionário: <b>$funcionario</b>, editou o funcionario <b>$idfuncionario</b> no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                               {$idfuncionario},
                                               'edição',
                                               '{$descricaoLog}',
                                               'funcionario',
                                               '{$idfuncionario}',
                                               null,
                                               '$date',
                                               '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
            
            $text = "Dados editados com sucesso";
            $type = 0;
        } else {
            $text = "Erro editar dados";
            $type = 1;
        }
    } else {
        $text = "Login <b>$login</b> em uso";
        $type = 1;
    }

    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}
