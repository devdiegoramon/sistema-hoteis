<?php
include "../../config/config.php";
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";
    $dataAtual = date("Y-m-d");
    ?>

    <div class="table-responsive col-lg-12"> 
        <table style="border-radius:10px;" class="table-card table table-hover table-secondary table-striped" id="datatable" "> 
            <thead style="border-radius:3em;"> 
                <tr> 
                    <th> ID </th>
                    <th> Acomodação </th>
                    <th> Cliente  </th>
                    <th> Entrada prevista</th>
                    <th> Saida prevista </th>
                    <th> Situação </th>
                    <th> Ação </th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Checkin para hoje
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
                                AND entradaprevista <= date('{$dataAtual}')
                                         ";
                $resultCheckinHj = mysqli_query($con, $sqlCheckinHj);
                while ($rowCheckinHj = mysqli_fetch_array($resultCheckinHj)) {
                    echo "
                        <tr> 
                            <td> $rowCheckinHj[0] </td>
                            <td> $rowCheckinHj[4] </td>
                            <td> $rowCheckinHj[5] </td>
                            <td> ". dataBrasil($rowCheckinHj[1])." </td>
                            <td> ". dataBrasil($rowCheckinHj[2])." </td>
                            <td> ". statusReserva($rowCheckinHj[3], 1)." </td>
                            <td class='text-center' title='visualizar'> <a href='../reserva/visualizarReserva.php?id=$rowCheckinHj[0]' class='badge-card badge bg-blue1'> <i class='fa-solid fa-eye'></i> </a> </td>    
                        </tr>
                        ";
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