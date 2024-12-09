<?php

if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";

    $modelo = $_POST['modelo'];
    $patrimonio = $_POST['patrimonio'];
    $idacomodacao = $_POST['acomadacao'];
  

    if (empty(trim($modelo)) or empty(trim($patrimonio))) {
        $text = "Por favor, preencha todos os campos.";
        $type = 2;
        header("Location: ../index.php?text=$text&type=$type");
        return;
    }

    $sqlConferePatrimonio = "SELECT 1 FROM frigobar WHERE patrimonio = '{$patrimonio}'";
    $resultSqlConfere = mysqli_query($con, $sqlConferePatrimonio);
    if (mysqli_num_rows($resultSqlConfere) == 0) {
        $sqlCadastro = "INSERT INTO frigobar VALUES (null,
                                                    {$idacomodacao},
                                                    '{$modelo}',
                                                    '{$patrimonio}',
                                                    's')";
        if (mysqli_query($con, $sqlCadastro)) {
            $idfrigobar = mysqli_insert_id($con);
            //GERA LOG
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");

            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou um frigobar com patrimônio: <b>$patrimonio</b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'cadastro',
                                              '{$descricaoLog}',
                                              'frigobar',
                                              {$idfrigobar},
                                              null,    
                                              '$date',
                                              '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
            //FIM LOG

            $text = "Frigobar <b>cadastrado</b> com sucesso";
            $type = 0;
        }
    } else {
        $text = "Frigobar <b>não</b> cadastrado: Patrimonio $patrimonio está em uso";
        $type = 2;
    }
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

