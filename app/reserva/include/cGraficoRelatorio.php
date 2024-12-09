<?php

include "../../config/config.php";

if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";

//Condição caso selecionem um ranged de datas
    $condicaoGrafico = "";
    $condicaoCalendar = "";
    if ($_POST['start'] != '0') {
        $start = dataAmerica($_POST['start']);
        $end = dataAmerica($_POST['end']);
        $condicaoGrafico = "AND (entradaprevista >= date('{$start}') and saidaprevista <= date('{$end}')
                        OR saidaprevista >= date('{$start}') and entradaprevista <= date('{$end}'))";

    }

//Quantidade total de reservas (todas acomodaoes)
    $dados = [
        'acomodacao' => [],
        'quantidade' => []
    ];

    //DADOS PARA O GRAFICO
    $sqlAcomodacoes = "SELECT idacomodacao,
                              nome
                           FROM acomodacao";

    $resultAcomodacoes = mysqli_query($con, $sqlAcomodacoes);
    while ($rowAcomodacoes = mysqli_fetch_array($resultAcomodacoes)) {
        $sqlQuantReservas = "SELECT COUNT(1)
                                 FROM reserva
                                 WHERE idacomodacao = {$rowAcomodacoes[0]}
                                 $condicaoGrafico
            ";
        $resultQuantReservas = mysqli_query($con, $sqlQuantReservas);
        $rowQuantReservas = mysqli_fetch_array($resultQuantReservas);
        $dados['acomodacao'][] = $rowAcomodacoes[1]; //Nome da acomodacao
        $dados['quantidade'][] = $rowQuantReservas[0]; //Quantidade de reservas
    }



    echo json_encode($dados);
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

    