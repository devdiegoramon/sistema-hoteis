<?php

include "../../config/config.php";

if (isset($_POST['adicionar'])) {
    include "../../config/connMysql.php";
    $idvaga = $_POST['add'];
    $idacomodacao = $_POST['idacomodacao'];
    $nomeacomodacao = $_POST['nomeacomodacao'];

    $sqlVaga = "UPDATE estacionamento
                SET idacomodacao = {$idacomodacao}
                WHERE idvaga = {$idvaga}";
    if (mysqli_query($con, $sqlVaga)) {
        //GERA LOG
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $descricaoLog = "Funcionário: <b>$funcionario</b>, adicionou a vaga de estacionamento de número <b>$numero</b> para a acomodação $nomeacomodacao, no dia <b>$data</b> às <b>$hora</b>";
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

        $text = "Vaga de estacionamento adicionada com sucesso";
        $type = 0;
    } else {
        $text = "Erro ao cadastrar vaga de estacionamento";
        $type = 1;
    }

    mysqli_close($con);
    header("Location: ../visualizarAcomodacao.php?id=$idacomodacao&text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}
