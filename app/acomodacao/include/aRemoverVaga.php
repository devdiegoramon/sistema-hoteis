<?php

include "../../config/config.php";

if (isset($_GET['idvaga'])) {
    include "../../config/connMysql.php";
    $idvaga = $_GET['idvaga'];
    $idacomodacao = $_GET['idacomodacao'];
    $nomeacomodacao = $_GET['nomeacomodacao'];

    $sqlVaga = "UPDATE estacionamento 
                SET idacomodacao = null 
                WHERE idvaga = {$idvaga}";
    if (mysqli_query($con, $sqlVaga)) {
        //GERA LOG
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $descricaoLog = "Funcionário: <b>$funcionario</b>, retirou a vaga de estacionamento de número <b>$numero</b> da acomodação $nomeacomodacao, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'edição',
                                              '{$descricaoLog}',
                                              'estacionamento',
                                              {$idestacionamento},
                                              null,
                                              '$date',
                                              '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        //FIM LOG

        $text = "Vaga de estacionamento retirada com sucesso";
        $type = 0;
    } else {
        $text = "Erro ao cadastrar retirar de estacionamento";
        $type = 1;
    }

    mysqli_close($con);
    header("Location: ../visualizarAcomodacao.php?id=$idacomodacao&text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}
