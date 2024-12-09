<?php
include "../../config/config.php";
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";
    $dataAtual = date("Y-m-d");
    ?>
    
    <div class="table-responsive col-lg-12"> 
        <table style="border-radius:10px;" class="table-card table table-secondary table-hover table-striped" id="datatable" "> 
            <thead style="border-radius:3em;"> 
                <tr> 
                    <th> ID </th>
                    <th> Nome </th>
                    <th> Número </th>
                    <th> Valor </th>
                    <th> Próxima reserva </th>
                    <th> Ação </th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Acomodações disponíveis hoje
                $sqlAcomodacao = "SELECT idacomodacao,
                                      nome,
                                      numero,
                                      valor
                                FROM acomodacao";
                $resultAcomodacao = mysqli_query($con, $sqlAcomodacao);
                while ($rowAcomodacao = mysqli_fetch_array($resultAcomodacao)) {
                    //Acomodações disponíveis hoje
                    $sqlAcDisponiveis = "SELECT idreserva FROM reserva
                                         WHERE (status = 'i' OR (entradaprevista = '{$dataAtual}' AND status = 'p'))
                                         AND idacomodacao = {$rowAcomodacao[0]}";
                    $resultAcDisponiveis = mysqli_query($con, $sqlAcDisponiveis);
                    if (mysqli_num_rows($resultAcDisponiveis) == 0) {
                        $sqlProxReserva = "SELECT MIN(entradaprevista)
                                           FROM reserva
                                           WHERE idacomodacao = {$rowAcomodacao[0]}
                                           AND status != 'c'    
                                           ";
                        $resultProxReserva = mysqli_query($con, $sqlProxReserva);
                        $rowProxReserva = mysqli_fetch_array($resultProxReserva);
                        echo "
                            <tr> 
                                <td> $rowAcomodacao[0] </td>
                                <td> $rowAcomodacao[1] </td>
                                <td> $rowAcomodacao[2] </td>
                                <td> R$ " . converteReal($rowAcomodacao[3]) . " </td>
                                <td> " . dataBrasil($rowProxReserva[0]) . " </td>
                                <td class='text-center' title='visualizar'> <a href='../acomodacao/visualizarAcomodacao.php?id=$rowAcomodacao[0]' class='badge-card badge bg-blue1'> <i class='fa-solid fa-eye'></i> </a> </td>    
                            </tr>
                            ";
                    }
                }
                ?>
        </table>
    </tbody>
    </div>
    <?php
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}