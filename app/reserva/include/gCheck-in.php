<?php

if (isset($_POST['idreserva'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $idreserva = $_POST['idreserva'];
    if (isset($_POST['data-checkin']) and isset($_POST['data-checkin'])) {
        $dataCheck = dataAmerica($_POST['data-checkin']);
        $horaCheck = dataAmerica($_POST['hora-checkin']);
    } else {
        $dataCheck = date("Y-m-d");
        $horaCheck = date('H:i:s');
    }
    $sqlChek = "UPDATE reserva SET datacheckin = '{$dataCheck}',
                                   horacheckin = '{$horaCheck}',
                                   status = 'i'    
                WHERE idreserva = {$idreserva}";
    if (mysqli_query($con, $sqlChek)) {
        $dadosCheckin = [];
        $dadosCheckin = ([
            'idreserva' => $idreserva,
            'acao' => 'check-in',
            'nomefuncionario' => $_SESSION['nome'],
            'datacheckin' => $dataCheck,
            'horacheckin' => $horaCheck    
        ]);

        //LOG
        $date = date("Y-m-d");
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $descricaoLog = "Funcionário: <b>$funcionario</b>, realizou o Check-in na reserva <b>Nº $idreserva</b>, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                          {$idFuncionario},
                                          'checkin',
                                          '{$descricaoLog}',
                                          'reserva',    
                                          {$idreserva},
                                          '" . json_encode($dadosCheckin) . "',
                                          '$date',
                                          '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        //FIM LOG

        $text = "Check-in realizado com sucesso";
        $type = 0;
    } else {
        $text = "Erro ao realizar check-in: Favor entrar em contato com o administrador do sistema";
        $type = 2;
    }

    mysqli_close($con);
    header("Location: ../visualizarReserva.php?id=$idreserva&text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}


    