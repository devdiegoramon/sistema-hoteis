<?php

if (isset($_POST['seguranca'])) {
include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";

$inicio = date("Y-m-01"); //Primeiro dia do mês
$fim = new DateTime(date("Y-m-01"));
$fim = $fim->format('Y-m-t'); //Ultimo dia do mês

$dados = array(
    'lucroMes' => array(),
    'labelMes' => array(),
    'totalMes' => array()
);

// LUCRO RECEBIDO DO MÊS 
$valorFinalMes = 0;
$valorEstadiaMes = 0;
$valorItensMes = 0;
$sqlValorMes = "SELECT valorestadia,
                       valoritens,
                       valorfinal,
                       datafechamento
                FROM consumo 
                WHERE datafechamento BETWEEN date('{$inicio}') AND date('{$fim}')
                ORDER BY datafechamento";
$resultValorMes = mysqli_query($con, $sqlValorMes);

while ($rowMes = mysqli_fetch_array($resultValorMes)) {
    $diafechamento = explode('-', $rowMes[3]);
    $diafechamento = "Dia $diafechamento[2]";
    if ($rowMes[1] == null) {
        $itensMes = 0;
    } else {
        $itensMes = $rowMes[1];
    }
    $valorFinalMes = $valorFinalMes + $rowMes[2];
    $valorEstadiaMes = $valorEstadiaMes + $rowMes[0];
    $valorItensMes = $valorItensMes + $itensMes;

    array_push($dados['lucroMes'], $rowMes[2]);
    array_push($dados['labelMes'], $diafechamento);
}
    array_push($dados['totalMes'], "R$ ".$valorFinalMes);


echo json_encode($dados);

} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}