<?php

if (isset($_POST['seguranca']) or isset($_POST['start'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $totalvalor = 0;
    $dados = array(
        'label' => array(),
        'valores' => array(),
        'total' => array(),
    );

    $i = 5; //Contador de meses
    if (isset($_POST['start'])) {
        $i = 0;
        $start = dataAmerica($_POST['start']);
        $end = dataAmerica($_POST['end']);
    }

    //Nomes dos ultimos 6 meses (contando o mês atual)
    for ($i; $i >= 0; $i--) {
        if (!isset($_POST['start'])) {
            $start = date('Y-m-01', strtotime("-$i months", strtotime(date('Y-m-d')))); //5 meses atrás (primeiro dia)
            $mes = explode('-', $start);
            $end = new DateTime("$start");
            $end = $end->format('Y-m-t'); //ultimo dia do mês
            $mes = strtolower(substr(nomeMes($mes[1]), 0, 3));
            array_push($dados['label'], $mes);

            $valorMes = 0;
            $sql = "SELECT valorfinal 
                    FROM consumo
                    WHERE datafechamento BETWEEN date('{$start}') AND date('{$end}')
                    AND status = 'concluido'";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $totalvalor = $totalvalor + $row[0];
                    $valorMes = $valorMes + $row[0];
                }
            }
            array_push($dados['valores'], converteDecimal($valorMes));
        } else {
            $dateStart = new DateTime($start);
            $dateEnd = new DateTime($end);

            //Invervalo entre as duas datas
            while ($dateStart <= $dateEnd) {
                $dados['label'][] = $dateStart->format('Y-m-d');
                $dateStart = $dateStart->modify('+1day');
            }

            $valorMes = 0;
            $sql = "SELECT valorfinal 
                    FROM consumo
                    WHERE datafechamento BETWEEN date('{$start}') AND date('{$end}')
                    AND status = 'concluido'";
            $result = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($result)) {
                $totalvalor = $totalvalor + $row[0];
                $valorMes = $valorMes + $row[0];
            }
            array_push($dados['valores'], converteDecimal($valorMes));
        }
    }
    die();
    //Total recebido 
    array_push($dados['total'], "R$ " . converteReal($totalvalor));
    echo json_encode($dados);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}    