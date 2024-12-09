<?php

include "../../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

function gerarComprovante($nomeComprovante, $idReserva, $con) {
    $tempNomeComprovante = explode('.', $nomeComprovante);
    $codComprovante = $tempNomeComprovante[0];

    /* Dados da reserva */
    $sqlReserva = "SELECT r.idreserva,
                          r.idacomodacao,
                          r.idcliente,
                          r.quantidadehospedes,
                          r.entradaprevista,
                          r.saidaprevista,
                          r.datacheckin,
                          r.horacheckin,
                          r.datacheckout,
                          r.horacheckout,
                          r.obs,
                          a.nome,
                          a.numero,
                          r.valordiaria,
                          c.nome,
                          c.cpf,
                          con.valorfinal,
                          con.totaldesconto,
                          con.formapagamento,
                          con.valorestadia,
                          con.valoritens,
                          con.status,
                          con.idconsumo
                    FROM reserva r        
                    LEFT JOIN cliente c ON (r.idcliente = c.idcliente)
                    LEFT JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                    LEFT JOIN consumo con ON (con.idreserva = r.idreserva)
                WHERE r.idreserva = {$idReserva}";
    $resultReserva = mysqli_query($con, $sqlReserva);
    $rowReserva = mysqli_fetch_array($resultReserva);

    /* Nome e caminho da logo */
    $sqlLogo = "select logoserver,
                       logonome
                from logo";
    $resultLogo = mysqli_query($con, $sqlLogo);
    if (mysqli_num_rows($resultLogo) > 0) {
        $caminhoLogo = "hospedagem/arquivos/logo/" . mysqli_fetch_array($resultLogo)[0];
        $logo = "<img style='max-width:150px; max-height:120px; margin:0 auto;' src='" . BASED . "/$caminhoLogo'>";
    } else {
        $logo = "";
    }


    /* Dados do estabelecimento */
    $sqlDadosEstabelecimento = "select cnpj, 
                                       razaosocial,
                                       website,
                                       email,
                                       telefone,
                                       celular
                                from estabelecimento";
    $resultDadosEstabelecimento = mysqli_query($con, $sqlDadosEstabelecimento);
    $rowEstabelecimento = mysqli_fetch_array($resultDadosEstabelecimento);
    $razaoSocial = mysqli_num_rows($resultDadosEstabelecimento) > 0 ? $rowEstabelecimento[1] : '';

//    /* Endereço do estabelecimento */
//    $sqlEndereco = "select logradouro,
//                           numero,
//                           complemento,
//                           cidade,
//                           bairro,
//                           cep
//                    from enderecoestabelecimento";
//    $resultEndereco = mysqli_query($con, $sqlEndereco);
//    $rowEndereco = mysqli_fetch_array($resultEndereco);

    $html = "  
            <style> 
                table {
                    font-family: sans-serif;
                    font-size:15px;
                }
                #header {
                    border: 1px solid #424949; 
                    padding :2px;
                    width: 100%;
                    margin:0 auto;
                }
                #header tr td {
                    color: #1B4F72;
                }
                
                #header_client {
                    padding:5px;
                    margin-top:25px;
                    width: 100%;
                    margin-bottom:20px;
                }
               
                .titulo_comprovante {
                    padding-right:60px;
                    text-align:center;
                    color: #1B4F72;
                }
                .dados_comprovante{
                    padding-left: 10px;
                }
                .text_left{
                    width:190px;
                    padding-right:-10px;
                }
                .text_right{
                    padding-left: 10px;
                }
                
                .row_dados_cliente{
                    border-top: 1px solid #424949; 
                    color: #1B4F72;
                    padding-bottom: 5px;
                }
            </style>
            <table id='header'>
                <tr>
                    <td class='titulo_comprovante' rowspan='1' >  
                        $logo
                    </td>
                    <td class='dados_comprovante text_left'> 
                        <span> <b> Código do comprovante </b> </span> <br>
                        <span> <b> Número da reserva </b></span> <br>
                        <span> <b> Forma de Pagamento </b> <br>
                        <span> <b> Pagamento realizado em </b> </span> <br>
                    </td>
                    <td class='dados_comprovante text-right'> 
                        <span>: $codComprovante </span> <br>
                        <span>: $idReserva</span> <br>
                        <span>: $rowReserva[18]</span> <br>
                        <span>: " . date('d/m/Y') . "</span> <br>
                    </td>
                </tr>
                 <tr> 
                    <td  style='padding-left:20px;' colspan='3'> <h5> $razaoSocial </h5> </td>
                </tr>
            </table>
            
            <table id='header_client'> 
                <tr> 
                    <td colspan='3' style='text-align:center; background-color:#E5E8E8; padding:5px;'> <span style='color:#1B4F72;'> Comprovante de pagamento  </span></td>
                </tr>
                
                <tr> 
                    <td colspan='2' style='color:#1B4F72; padding-top:10px;'> <span> Cliente </span> </td>
                </tr>
                <tr style='border-bottom:1px solid black;'> 
                    <td> Nome do cliente: <span> $rowReserva[14]</span> </td>
                    <td> CPF: <span> $rowReserva[15]</span> </td>
                </tr> 
                
                <tr>
                    <td style='padding-top:15px;' class='row_dados_cliente' colspan='3'> <span>Reserva </span></td>
                </tr>    
                <tr> 
                    <td> Quantidade de hospedes: <span> $rowReserva[3] </span> </td>
                </tr>
                <tr> 
                    <td> Quarto: <span>Nº$rowReserva[12] -  $rowReserva[11]</span> </td>
                </tr>
                <tr> 
                    <td> Valor da diária: R$ " . converteReal($rowReserva['valordiaria']) . " </td>
                </tr>
                <tr> 
                    <td> Check-in: <span> " . dataBrasil($rowReserva[6]) . " às " . substr($rowReserva[7], 0, 5) . "</span> </td>
                </tr>
                <tr > 
                    <td> Check-out: <span> " . dataBrasil($rowReserva[8]) . " às " . substr($rowReserva[9], 0, 5) . "</span> </td>
                </tr>
                <tr> 
                    <td> Desconto aplicado: <span> R$ " . converteReal($rowReserva['totaldesconto']) . "</span> </td>
                </tr>
                <tr> 
                    <td> Valor final: <span> R$ " . converteReal($rowReserva['valorfinal']) . "</span> </td>
                </tr>
                </table>
                 <div style='width:49%; float:left;'> 
                         <table style='width:100%;'>  
                            <thead> 
                                <tr style='border:none;'> 
                                    <th  colspan='4' style='text-align:center; background-color:#E5E8E8;'><span style='color:#1B4F72;'> Itens Consumidos </span> </th>
                                </tr>
                                <tr> 
                                    <th style='text-align:left; font-size: 13px;'> Item </th>
                                    <th style='text-align:left; font-size: 13px;'> Valor </th>
                                    <th style='text-align:left; font-size: 13px; padding:0 10px;'> Quant </th>
                                    <th style='text-align:left; font-size: 13px;'> Data </th>
                                </tr>'
                            </thead>
                            <tbody>";

    $sqlItensConsumidos = "SELECT nome,
                                  quantidade,
                                  valortotal,
                                  datag,
                                  horag
                             FROM itensconsumidos
                           WHERE idconsumo = {$rowReserva['idconsumo']}";
    $resultItensConsumidos = mysqli_query($con, $sqlItensConsumidos);

    if (mysqli_num_rows($resultItensConsumidos) > 0) {
        while ($rowItens = mysqli_fetch_array($resultItensConsumidos)) {
            $html .= "
                                <tr> 
                                    <td style='font-size: 11px;'> $rowItens[0] </td>
                                    <td style='font-size: 11px;'> R$ " . converteReal($rowItens[2]) . " </td>
                                    <td style='font-size: 11px; text-align:center;'> $rowItens[1] </td>
                                    <td style='font-size: 11px;'> " . dataBrasil($rowItens[3]) . " às $rowItens[4] </td>
                                </tr>
                ";
        }
    } else {
        $html .= "
                                <tr> 
                                    <td style='font-size: 11px;'>*</td>
                                    <td style='font-size: 11px;'></td>
                                    <td style='font-size: 11px;'></td>
                                    <td style='font-size: 11px;'><td>
                                </tr>
                ";
    }

    $sqlConsumoAdicional = "SELECT motivo, 
                                   valor
                                FROM adicionalconsumo
                            WHERE idconsumo = {$rowReserva['idconsumo']}";
    $resultConsumoAdicional = mysqli_query($con, $sqlConsumoAdicional);
    $marginTop = '';

    $html .= "              </tbody>
                        </table>
                         </div>
                        <div style='width:49%; float:right;'> 
                         <table style='width:100%;'>  
                            <thead> 
                                <tr style='border:none;'> 
                                    <th style='text-align:center; background-color:#E5E8E8;' colspan='2'><span style='color:#1B4F72;'> Consumo Adicional </span> </th>
                                </tr>
                                <tr> 
                                    <th style='text-align:left; font-size: 13px;'> Descrição </th>
                                    <th style='text-align:left; font-size: 13px;'> Valor </th>
                                </tr>'
                            </thead>
                            <tbody>";

    if (mysqli_num_rows($resultConsumoAdicional) > 0) {
        while ($rowAdicional = mysqli_fetch_array($resultConsumoAdicional)) {
            $html .= "
                                <tr> 
                                    <td style='font-size: 11px;'> $rowAdicional[0] </td>
                                    <td style='font-size: 11px;'> R$ " . converteReal($rowAdicional[1]) . " </td>
                                </tr>
                ";
        }
    } else {
        $html .= "
                                <tr> 
                                    <td style='font-size: 11px;'> * </td>
                                    <td style='font-size: 11px;'></td>
                                </tr>
                ";
    }

    $html .= "             </tbody>
                        </table>
                        </div>
            ";

//FIM PDF
     $options = new Options();
    $options->setIsRemoteEnabled(true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->set_paper('a4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    $dompdf->stream("nomeDoArquivo", ["Attachment" => false]);
    
    file_put_contents("../../comprovantes/pagamentos/$nomeComprovante", $output);
//    $mpdf->Output();
//    $mpdf = new \Mpdf\Mpdf();
//    $mpdf->SetDisplayMode('fullpage');
//    $mpdf->WriteHTML($html);
//    $mpdf->Output("../../comprovantes/pagamentos/$nomeComprovante", "F");

    $_SESSION['idreservacomprovante'] = $idReserva;
}
