<?php
include "../../config/config.php";
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";
    $dataAtual = date("Y-m-d");
    $dataSemana = date('Y-m-d', strtotime("-7 day", strtotime(date('Y-m-d'))));
    ?>

    <div class="table-responsive"> 
        <table class="table-card table table-secondary table-striped" id="datatable"> 
            <thead style="border-radius:3em;"> 
                <tr> 
                    <th> ID </th>
                    <th> Acomodação </th>
                    <th> Cliente </th>
                    <th> Entrada prevista </th>
                    <th> Saida prevista </th>
                    <th class="text-center"> Situação </th>
                    <th class="text-center"> Ação </th>
                </tr>
            </thead>
            <tbody> 
                <?php
                $sqlUltimaSemana = "SELECT r.idreserva,
                                           r.idacomodacao,
                                           r.entradaprevista,
                                           r.saidaprevista,
                                           r.status,
                                           a.nome,
                                           c.nome
                                    FROM reserva r       
                                    INNER JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                                    INNER JOIN cliente c ON (r.idcliente = c.idcliente)
                                    WHERE (r.entradaprevista >= date('{$dataSemana}') and saidaprevista <= date('{$dataAtual}')
                                    OR saidaprevista >= date('{$dataSemana}') and entradaprevista <= date('{$dataAtual}'))";
                $resultUltimaSemana = mysqli_query($con, $sqlUltimaSemana);
                while ($rowUltimaSemana = mysqli_fetch_array($resultUltimaSemana)) {
                    ?>
                    <tr> 
                        <td> <?= $rowUltimaSemana[0] ?> </td>
                        <td> <?= $rowUltimaSemana[5] ?> </td>
                        <td> <?= $rowUltimaSemana[6] ?> </td>
                        <td> <?= dataBrasil($rowUltimaSemana[2]) ?> </td>
                        <td> <?= dataBrasil($rowUltimaSemana[3]) ?> </td>
                        <td class="text-center"> <?= statusReserva($rowUltimaSemana[4], 1) ?> </td> 
                        <td class="text-center" title="visualizar"> <a href="../reserva/visualizarReserva.php?id=<?= $rowUltimaSemana[0] ?>" class="badge-card badge bg-blue1"> <i class="fa-solid fa-eye"></i> </a> </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}