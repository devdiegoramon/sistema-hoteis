<?php
include "../../config/config.php";
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";
    $dataAtual = date("Y-m-d");
    ?>

    <div class="table-responsive col-lg-12"> 
        <table style="border-radius:10px;" class="table-card table-hover table table-secondary table-striped" id="datatable" "> 
            <thead style="border-radius:3em;"> 
                <tr> 
                    <th> ID </th>
                    <th> Nome </th>
                    <th> Número  </th>
                    <th> Valor (diaria)</th>
                    <th class="text-center"> Reservado de : </th>
                    <th class="text-center"> Ação </th>
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
                    $sqlAcOcupadas = "SELECT entradaprevista,
                                             saidaprevista
                                         FROM reserva
                                         WHERE (status = 'i' OR (entradaprevista = '{$dataAtual}' AND status = 'p'))
                                         AND idacomodacao = {$rowAcomodacao[0]}";
                    $resultAcOcupadas = mysqli_query($con, $sqlAcOcupadas);
                    $rowAcOcupadas = mysqli_fetch_array($resultAcOcupadas);
                    if (mysqli_num_rows($resultAcOcupadas) > 0) {
                        echo "
                            <tr> 
                                <td> $rowAcomodacao[0] </td>
                                <td> $rowAcomodacao[1] </td>
                                <td> $rowAcomodacao[2] </td>
                                <td> R$ " . converteReal($rowAcomodacao[3]) . " </td>
                                <td class='text-center'> " . dataBrasil($rowAcOcupadas[0]) . " até " . dataBrasil($rowAcOcupadas[1]) . " </td>
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