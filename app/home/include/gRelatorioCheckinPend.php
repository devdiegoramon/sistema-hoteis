<?php

include "../../config/config.php";
include "../../../vendor/autoload.php";

use Mpdf\Mpdf;

if (isset($_GET['gerar'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";

    $dataAtual = date("Y-m-d");

    $sqlCheckinHj = "SELECT r.idreserva,
                            r.entradaprevista,
                            r.saidaprevista,
                            r.status,
                            a.nome,
                            c.nome
                    FROM reserva r       
                        INNER JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                        INNER JOIN cliente c ON (r.idcliente = c.idcliente)
                    WHERE status = 'p'
                    AND entradaprevista <= date('{$dataAtual}')";
    $resultCheckinHj = mysqli_query($con, $sqlCheckinHj);

    //PDF
    $html = "
            <style>
                table {
                    width:100%;
                    border-collapse:collapse;
                }
                td {
                    border: 1px solid black;
                }
                td {
                     padding:3px 10px;
                }

                .colunas td {
                      font-size:13px;
                }

                .corpo td{
                      font-size:12px;
                }

            </style>
            <div style = 'border-bottom:1px solid dark-gray; margin-bottom:10px;'>
                 <h3> Reservas com check-in pendentes até dia " . date("d/m/Y") . "</h3>
            </div>
            <table>
                 <tbody>
                    <tr class = 'colunas'>
                        <td> ID </td>
                        <td> Acomodacao </td>
                        <td> Cliente </td>
                        <td style='text-align:center;'> Entrada prevista </td>
                        <td style='text-align:center;'> Saida prevista </td>
                        <td style='text-align:center;'> Situação </td>
                    </tr>
            ";
    while ($rowCheckinHj = mysqli_fetch_array($resultCheckinHj)) {
        $html .= "
                <tr> 
                    <td> $rowCheckinHj[0] </td>
                    <td> $rowCheckinHj[4] </td>
                    <td> $rowCheckinHj[5] </td>
                    <td> " . dataBrasil($rowCheckinHj[1]) . " </td>
                    <td> " . dataBrasil($rowCheckinHj[2]) . " </td>
                    <td> " . statusReserva($rowCheckinHj[3], 0) . " </td>
                </tr>
                ";
    }

    $html .= "  </tbody>
            </table> ";
    //FIM PDF

    $mpdf = new mPDF();
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML($html);
    $mpdf->Output();
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text = $text&type = 1");
}
