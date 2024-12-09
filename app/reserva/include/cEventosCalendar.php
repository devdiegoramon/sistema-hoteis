<?php

include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";

$acomodacao = $_POST['acomodacao'];

//Condição caso selecionem um ranged de datas
$condicaoGrafico = "";
if ($_POST['start'] != '0') {
    $start = dataAmerica($_POST['start']);
    $end = dataAmerica($_POST['end']);
    $condicaoGrafico = "AND (entradaprevista >= date('{$start}') and saidaprevista <= date('{$end}')
                        OR saidaprevista >= date('{$start}') and entradaprevista <= date('{$end}'))";
}

//Quantidade total de reservas (todas acomodaoes)
$quantReservas = [
    'acomodacao' => [],
    'quantidade' => []
];

if ($acomodacao == 'todas') {
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
        $quantReservas['acomodacao'][] = $rowAcomodacoes[1]; //Nome da acomodacao
        $quantReservas['quantidade'][] = $rowQuantReservas[0]; //Quantidade de reservas
    }
}
echo json_encode($quantReservas);
mysqli_close($con);

