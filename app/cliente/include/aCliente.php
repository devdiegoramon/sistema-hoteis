<?php

if (isset($_POST['editar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $idcliente = $_POST['idcliente'];
    $nome = $_POST['nome'];
    $dtnasc = dataAmerica($_POST['dtnasc']);
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];

    $dataIdade = new DateTime($dtnasc);
    $idade = $dataIdade->diff(new DateTime(date('Y-m-d')));
    $idade = $idade->format('%Y'); //IDADE DO CLIENTE

    if ($idade >= 18) {
        $sqlConfereCpf = "SELECT 1 FROM cliente WHERE cpf = '{$cpf}' AND idcliente != $idcliente"; //CONFERE SE O CLIENTE JÁ ESTÁ
        $resultConfereCpf = mysqli_query($con, $sqlConfereCpf);
        $sqlConfereEmail = "SELECT 1 FROM cliente WHERE email = '{$email}' AND idcliente != $idcliente";
        $resultConfereEmail = mysqli_query($con, $sqlConfereEmail);
        //VERIFICA SE NOME E NUMERO JÁ ESTÃO CADASTRADOS
        if ((mysqli_num_rows($resultConfereCpf) > 0) and (mysqli_num_rows($resultConfereEmail) > 0)) {
            $text = "Dados não atualizados: CPF e endereço de e-mail já cadastrado anteriormente";
            $type = 2;
        } elseif (mysqli_num_rows($resultConfereCpf) > 0) {
            $text = "Dados não atualizados: CPF já cadastrado anteriormente";
            $type = 2;
        } elseif (mysqli_num_rows($resultConfereEmail) > 0) {
            $text = "Dados não atualizados: Endereço de e-mail já cadastrado anteriormente";
            $type = 2;
        } else {

            $sql = "UPDATE cliente SET  nome = '{$nome}',
                                cpf = '{$cpf}',
                                dtnasc = '{$dtnasc}',
                                email = '{$email}',
                                telefone = '{$telefone}',
                                estado = '{$estado}',
                                cidade = '{$cidade}'
            WHERE idcliente = {$idcliente}";
            if (mysqli_query($con, $sql)) {
                if (mysqli_affected_rows($con)) { //VERIFICA SE HOUVE ALTERAÇÕES 
                    //GERA LOG
                    $idFuncionario = $_SESSION['idlogin'];
                    $funcionario = $_SESSION['login'];
                    $date = date("Y-m-d"); //FORMATO AMERICANO 
                    $data = date("d/m/Y");
                    $hora = date("H:i:s");
                    $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>editou</b> a cliente <b>Nº" . $idcliente . "</b> : <b>$nome</b>, no dia <b>$data</b> às <b>$hora</b>";
                    $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'edição de cliente',
                                              '{$descricaoLog}',
                                              'cliente',
                                              '{$idcliente}',
                                               null,
                                              '$date',
                                              '$hora')";
                    if (mysqli_query($con, $sqlLog)) {
                        
                    }
                    //FIM LOG
                    mysqli_close($con);
                }
                $text = "Dados alterados com sucesso";
                $type = 0;
            } else {
                $text = "Nenhuma alteração realizada";
                $type = 2;
            }
        }
    } else {
        $text = "Cadastro não realizado: Necessário ter 18 anos ou mais para continuar";
        $type = 2;
    }

    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}