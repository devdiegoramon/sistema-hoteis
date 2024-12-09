<?php

include "../../config/config.php";
include "../../../vendor/autoload.php";

use Mpdf\Mpdf;

if (isset($_GET['gerar'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";

    $dataAtual = date("Y-m-d");

    //Acomodações disponíveis hoje
    $sqlAcomodacao = "SELECT idacomodacao,
                             nome,
                             numero,
                             valor
                      FROM acomodacao";
    $resultAcomodacao = mysqli_query($con, $sqlAcomodacao);

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
                 <h3> Acomodações ocupadas no dia " . date("d/m/Y") . "</h3>
            </div>
            <table>
                 <tbody>
                    <tr class = 'colunas'>
                        <td> Id </td>
                        <td> Nome </td>
                        <td> Número </td>
                        <td> Valor (diaria) </td>
                        <td> Reservado de: </td>
                    </tr>
            ";
    while ($rowAcomodacao = mysqli_fetch_array($resultAcomodacao)) {
        //Acomodações disponíveis hoje
        $sqlAcOcupadas = "SELECT entradaprevista,
                                 saidaprevista
                          FROM reserva
                          WHERE (status = 'i' OR (entradaprevista = '{$dataAtual}' AND status = 'p'))
                          AND idacomodacao = {$rowAcomodacao[0]}";
        $resultAcOcupadas = mysqli_query($con, $sqlAcOcupadas);
        $rowAcOcupadas = mysqli_fetch_array($resultAcOcupadas);
        if (mysqli_num_rows($resultAcOcupadas) > 0) {
            $html .= "
                   <tr> 
                        <td> $rowAcomodacao[0] </td>
                        <td> $rowAcomodacao[1] </td>
                        <td> $rowAcomodacao[2] </td>
                        <td> R$ " . converteReal($rowAcomodacao[3]) . " </td>
                        <td class='text-center'> " . dataBrasil($rowAcOcupadas[0]) . " até " . dataBrasil($rowAcOcupadas[1]) . " </td>
                    </tr>
                    ";
        }
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
