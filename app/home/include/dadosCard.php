<?php

$dataAtual = date("Y-m-d");
$acomodacoesDisp = []; //ID das acomodações disponíveis

$quantidadeTodasAc = 0; //total de acomodações
$quantAcDisponiveis = 0; //Total de acomodacoes disponíveis hoje
$sqlTodasAc = "SELECT idacomodacao,
                      nome,
                      numero
               FROM acomodacao";
$resultTodasAc = mysqli_query($con, $sqlTodasAc);
while ($rowTodasAc = mysqli_fetch_array($resultTodasAc)) {
    $quantidadeTodasAc++;

    //Acomodações disponíveis
    $sqlAcDisponiveis = "SELECT 1 FROM reserva
                         WHERE (status = 'i' OR (entradaprevista = '{$dataAtual}' AND status = 'p'))
                         AND idacomodacao = {$rowTodasAc[0]}";
    $resultAcDisponiveis = mysqli_query($con, $sqlAcDisponiveis);
    if (mysqli_num_rows($resultAcDisponiveis) == 0) {
        $acomodacoesDisp[] = $rowTodasAc[0];
        $quantAcDisponiveis++;
    }
}

//Hospedes e acomodações
$quantidadeHospedes = 0; //total de hospedes atuais
$quantAcOcupada = 0; //Total de acomodacoes ocupadas
$sqlHospedes = "SELECT quantidadehospedes,
                       idacomodacao
                FROM reserva 
                WHERE status = 'i'";
$resultHospedes = mysqli_query($con, $sqlHospedes);
while ($rowHospedes = mysqli_fetch_array($resultHospedes)) {
    $quantidadeHospedes += $rowHospedes[0];
    $quantAcOcupada += $rowHospedes[1];
}

//Checkin realizados hoje
$checkinHj = 0;
$sqlCheckinHj = "SELECT 1 FROM reserva 
                WHERE datacheckin = '{$dataAtual}'";
$resultCheckinHj = mysqli_query($con, $sqlCheckinHj);
while ($rowCheckinHj = mysqli_fetch_array($resultCheckinHj)) {
    $checkinHj += $rowCheckinHj[0];
}

//Checkout realizados hoje
$checkoutHj = 0;
$sqlCheckoutHj = "SELECT 1 FROM reserva 
                  WHERE datacheckout = '{$dataAtual}'";
$resultCheckoutHj = mysqli_query($con, $sqlCheckoutHj);
while ($rowCheckoutHj = mysqli_fetch_array($resultCheckoutHj)) {
    $checkoutHj += $rowCheckoutHj[0];
}



    