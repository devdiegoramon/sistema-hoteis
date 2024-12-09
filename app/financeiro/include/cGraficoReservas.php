<?php

if (isset($_POST['start'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    //RANGED 
    $start = $_POST['start'][0];
    $end = $_POST['end'][0];

    $dados = array(
        'label' => array(),
        'valor' => array(),
        'total' => array(),
        'titulo' => array(
            "Acomodações com reservas finalizadas entre " . dataBrasil($start) . " e " . dataBrasil($end)
        )
    );

    $sqlAcomodacao = "SELECT idacomodacao,
                             nome
                      FROM acomodacao
                      WHERE ativo = 's'
                      ";
    $resultAcomodacao = mysqli_query($con, $sqlAcomodacao);
    $valorTotal = 0;
    //VERIFICA SE A ACOMODAÇÃO ESTÁ DISPONIVEL
    if (mysqli_num_rows($resultAcomodacao) > 0) {
        while ($rowAcomodacao = mysqli_fetch_array($resultAcomodacao)) {
            $valorReserva = 0;
            $sqlReserva = "SELECT c.valorfinal
                            FROM consumo c
                            INNER JOIN reserva r ON (c.idreserva = r.idreserva)
                            WHERE (r.entradaprevista >= date('{$start}') and r.saidaprevista <= date('{$end}')
                            OR r.saidaprevista >= date('{$start}') and r.entradaprevista <= date('{$end}'))
                            AND r.idacomodacao = {$rowAcomodacao[0]}
                            AND (r.status = 'f')";
            $resultReserva = mysqli_query($con, $sqlReserva);
            while ($rowReserva = mysqli_fetch_array($resultReserva)) {
                $valorReserva += $rowReserva[0];
            }
            $dados['label'][] = $rowAcomodacao[1];
            $dados['valor'][] = $valorReserva;
            $valorTotal += $valorReserva;
        }
    }
    $dados['total'][] = "R$ ". $valorTotal;
    echo json_encode($dados);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text = $text&type = 1");
}    