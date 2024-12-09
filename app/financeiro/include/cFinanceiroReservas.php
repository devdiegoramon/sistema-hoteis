<?php

include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";
include "../../include/components.php";

if (isset($_POST['start']) and isset($_POST['end'])) {
    $dados = array(
        'campos' => array(),
        'start' => array(),
        'end' => array()
    );
    if ($_POST['start'] == '0') {
        $start = date("Y-m-01"); //Primeiro dia do mês
        $end = new DateTime(date("Y-m-01"));
        $end = $end->format('Y-m-t'); //Ultimo dia do mês
    } else {
        $start = dataAmerica($_POST['start']);
        $end = dataAmerica($_POST['end']);
    }

    $campos = '';
    $sqlReservas = "SELECT r.idreserva,
                           a.nome,
                           c.nome,
                           r.quantidadehospedes,
                           r.entradaprevista,
                           r.saidaprevista,
                           r.status,
                           con.valorfinal
                     FROM reserva r 
                     INNER JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                     INNER JOIN cliente c ON (r.idcliente = c.idcliente)
                     INNER JOIN consumo con ON (con.idreserva = r.idreserva)
                     WHERE(entradaprevista >= date('{$start}') and saidaprevista <= date('{$end}')
                     OR saidaprevista >= date('{$start}') and entradaprevista <= date('{$end}'))
                     ";
    $resultReservas = mysqli_query($con, $sqlReservas);
    if (mysqli_num_rows($resultReservas) > 0) {
        while ($rowReservas = mysqli_fetch_array($resultReservas)) {
            $campos .= "
            <tr> 
                <td> Nº $rowReservas[0] </td>
                <td> $rowReservas[1] </td>
                <td> $rowReservas[2] </td>
                <td> " . dataBrasil($rowReservas[4]) . " até " . dataBrasil($rowReservas[5]) ." </td>
                <td> " . statusReserva($rowReservas[6], 1) . " </td>
                <td> R$ " . converteReal($rowReservas[7]) . " </td>
                <td class='text-center' title='visualizar'> <a href='../reserva/visualizarReserva.php?id=$rowReservas[0]' class='badge-card badge bg-blue1 text-dark'> <i class='fa-solid fa-eye'></i> </a> </td>
             </tr>
            ";
        }
    } else {
        $campos .= "<tr> 
                <td style='text-align:center; padding-top:25px;' colspan='5'> Nenhuma venda realizada entre " . dataBrasil($start) . " e " . dataBrasil($end) . " </td>
             </tr>";
    }
    $dados['campos'][] = $campos;
    $dados['start'][] = "$start";
    $dados['end'][] = "$end";

    echo json_encode($dados);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}
    