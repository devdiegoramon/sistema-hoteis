<?php

include "../../../vendor/autoload.php";
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

//chmod('C:\xampp\htdocs\projeto-pousada\app\cliente\include', 0777);
if (isset($_POST['gerar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $clientes = $_POST['clientes'];
    if (isset($_POST['start'])) {
        //CONDIÇÃO PARA CONSULTA COM RANGED DE DATAS
        $start = dataAmerica($_POST['start']); //INICIO DO RANGE
        $end = dataAmerica($_POST['end']); //FIM DO RANGE
        $periodo = "WHERE datag BETWEEN date('$start') and date('$end')";
        $ranged = "entre " . dataBrasil($start) . " e " . dataBrasil($end);
    } else {
        //CONDIÇÃO PARA CONSULTA SEM RANGED DE DATAS
        $periodo = "WHERE 1 > 0";
        $ranged = "(Todos os clientes cadastrados)";
    }

    $status = ""; //ativo = 's' / 'n'
    $condicaoCliente = ''; //'Ativos' ou 'Inativo' (usado na tabela)
    //CONDIÇAO PARA CONSULTA DE CLIENTES INATIVOS OU ATIVOS
    if ($clientes == 'ativos') {
        $status = "Ativos";
        $condicaoCliente = "AND ativo = 's'";
    } elseif ($clientes == 'inativos') {
        $status = "Inativos";
        $condicaoCliente = "AND ativo = 'n'";
    }
    //Dados do cliente
    $sqlCliente = "SELECT idcliente,
                            nome,
                            cpf,
                            telefone,
                            email,
                            ativo,
                            datag
                    FROM cliente
                    $periodo
                    $condicaoCliente";

    $resultCliente = mysqli_query($con, $sqlCliente);
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
                 <h3> Clientes $status $ranged</h3>
            </div>
            <table>
                 <tbody>
                    <tr class = 'colunas'>
                        <td> Id </td>
                        <td> Nome </td>
                        <td> Cpf </td>
                        <td> Contato </td>
                        <td> status </td>
                        <td> Data do cadastro </td>
                    </tr>
            ";
    while ($rowCliente = mysqli_fetch_array($resultCliente)) {

        if ($rowCliente[5] == 's') {
            $status = "Ativo";
        } else {
            $status = "Inativo";
        }
        $html .= "
                <tr class = 'corpo'>
                    <td> Nº " . $rowCliente[0] . " </td>
                    <td> " . $rowCliente[1] . " </td>
                    <td> " . $rowCliente[2] . " </td>
                    <td> " . $rowCliente[3] . " </td>
                    <td> " . $status . " </td>
                    <td> " . dataBrasil($rowCliente[6]) . " </td>
                </tr> ";
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
