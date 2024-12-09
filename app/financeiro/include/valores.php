<?php

$primeirodia = date("Y-m-01"); //´Primeiro dia do Mês
$ultimodia = new DateTime(date("Y-m-01"));
$ultimodia = $ultimodia->format('Y-m-t'); //Ultimo dia do mês

$primeiroDiaAno = date("Y-01-01");
$ultimoDiaAno = new DateTime(date("Y-12-01"));
$ultimoDiaAno = $ultimoDiaAno->format('Y-m-t');

// CALCULANDO LUCRO RECEBIDO DO MÊS 
$valorFinalMes = 0;
$valorEstadiaMes = 0;
$valorItensMes = 0;
$sqlValorMes = "SELECT idconsumo,
                       idreserva,
                       valorestadia,
                       valoritens,
                       valorfinal
                FROM consumo 
                WHERE datafechamento BETWEEN date('{$primeirodia}') AND date('{$ultimodia}')";
$resultValorMes = mysqli_query($con, $sqlValorMes);

while ($rowMes = mysqli_fetch_array($resultValorMes)) {
    if ($rowMes[3] == null) {
        $itensMes = 0;
    } else {
        $itensMes = $rowMes[3];
    }
    $valorFinalMes = $valorFinalMes + $rowMes[4];
    $valorEstadiaMes = $valorEstadiaMes + $rowMes[2];
    $valorItensMes = $valorItensMes + $itensMes;
}

//CALCULANDO VALOR A SER RECEBIDO NO MÊS
$valorPreMes = 0;
$sqlPreMes = "SELECT c.idconsumo,
                     c.idreserva,
                     c.valorfinal
              FROM consumo c
              INNER JOIN reserva r ON (c.idreserva = r.idreserva)
              WHERE saidaprevista BETWEEN date('{$primeirodia}') AND date('{$ultimodia}')
              AND r.status != 'c'
              AND r.status != 'f'
              ";
$resultPreMes = mysqli_query($con, $sqlPreMes);

while ($rowPreMes = mysqli_fetch_array($resultPreMes)) {
    $valorPreMes = $valorPreMes + $rowPreMes[2];
}
$valorPreMes = converteReal($valorPreMes);

//LUCRO RECEBIDO NO ANO
$valorAno = 0;
$sqlValorAno = "SELECT valorfinal
                FROM consumo 
                WHERE datafechamento BETWEEN date('{$primeiroDiaAno}') AND date('{$ultimoDiaAno}')";
$resultValorAno = mysqli_query($con, $sqlValorAno);

while ($rowAno = mysqli_fetch_array($resultValorAno)) {
    $valorAno = $valorAno + $rowAno[0];
}
$valorAno = converteReal($valorAno);

//LUCRO PREVISTO PARA O NO ANO
$valorPreAno = 0;
$sqlPreAno = "SELECT valorfinal
              FROM consumo c
              INNER JOIN reserva r ON (c.idreserva = r.idreserva)
              WHERE saidaprevista BETWEEN date('{$primeiroDiaAno}') AND date('{$ultimoDiaAno}')
              AND r.status != 'c'
              AND r.status != 'f'";
$resultPreAno = mysqli_query($con, $sqlPreAno);
while ($rowPreAno = mysqli_fetch_array($resultPreAno)) {
    $valorPreAno = $valorPreAno + $rowPreAno[0];
}
$valorPreAno = converteReal($valorPreAno);
