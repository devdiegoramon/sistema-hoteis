<?php

if (isset($_POST['idacomodacao'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $idacomodacao = $_POST['idacomodacao'];

    $dados = [
        'label' => [],
        'value' => [],
        'idreserva' => []
    ];

    $primeiroDia = date("Y-m-01");
    $i = 1;
    for ($i; $i <= 13; $i++) {
        $ultimoDia = new DateTime("$primeiroDia");
        $ultimoDia = $ultimoDia->format('Y-m-t');

        $sqlReservaMes = "SELECT count(1),
                            idreserva
                            FROM reserva
                            WHERE (entradaprevista >= date('{$primeiroDia}') and saidaprevista <= date('{$ultimoDia}')
                            OR saidaprevista >= date('{$primeiroDia}') and entradaprevista <= date('{$ultimoDia}'))
                            AND idacomodacao = {$idacomodacao};
                              ";
        $resultReserva = mysqli_query($con, $sqlReservaMes);
        while ($rowResultReserva = mysqli_fetch_array($resultReserva)) {

            $label = explode('/', dataBrasil($ultimoDia));
            unset($label[0]);
            $label = implode('/', $label);
            $dados['label'][] = $label;
            $dados['value'][] = $rowResultReserva[0];
            $dados['idreserva'][] = $rowResultReserva[1];
            $primeiroDia = date("Y-m-01", strtotime("-$i months", strtotime(date('Y-m-d'))));
        }
    }

    echo json_encode($dados);

    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}


