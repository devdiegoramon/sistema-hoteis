<?php

include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";

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
    $sqlVendas = "SELECT i.iditem,
                         i.nome,
                         i.quantidade,
                         i.valortotal,
                         i.datag,
                         c.idreserva
                 FROM itensconsumidos i
                 INNER JOIN consumo c ON (i.idconsumo = c.idconsumo)
                 WHERE datag BETWEEN date('{$start}') AND date('{$end}')";
    $resultVendas = mysqli_query($con, $sqlVendas);
    if (mysqli_num_rows($resultVendas) > 0) {
        while ($rowVendas = mysqli_fetch_array($resultVendas)) {
            $campos .= "
            <tr> 
                <td> Nº $rowVendas[5] </td>
                <td> $rowVendas[1] </td>
                <td> $rowVendas[2] </td>
                <td> R$ " . converteReal($rowVendas[3]) . " </td>
                <td> " . dataBrasil($rowVendas[4]) . " </td>
                <td class='text-center' title='visualizar'> <a href='../reserva/visualizarReserva.php?id=$rowVendas[5]' class='badge-card badge bg-blue1 text-dark'> <i class='fa-solid fa-eye'></i> </a> </td>
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
