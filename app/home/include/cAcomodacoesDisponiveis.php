<?php
include "../../config/config.php";
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";
    $dataAtual = date("Y-m-d");
    ?>
    
    <div class="table-responsive col-lg-12"> 
        <table style="border-radius:10px;" class="table-card table table-secondary table-hover table-striped" id="datatable"> 
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
                // Acomodações ativas disponíveis hoje
                $sqlAcomodacao = "SELECT idacomodacao, nome, numero, valor
                                  FROM acomodacao
                                  WHERE ativo = 's'";
                $resultAcomodacao = mysqli_query($con, $sqlAcomodacao);
                while ($rowAcomodacao = mysqli_fetch_array($resultAcomodacao)) {
                    $idacomodacao = intval($rowAcomodacao['idacomodacao']);
                    
                    // Acomodações disponíveis hoje
                    $sqlAcDisponiveis = "SELECT idreserva FROM reserva
                                         WHERE (status = 'i' OR (entradaprevista = ? AND status = 'p'))
                                         AND idacomodacao = ?";
                    $stmtAcDisponiveis = mysqli_prepare($con, $sqlAcDisponiveis);
                    mysqli_stmt_bind_param($stmtAcDisponiveis, "si", $dataAtual, $idacomodacao);
                    mysqli_stmt_execute($stmtAcDisponiveis);
                    $resultAcDisponiveis = mysqli_stmt_get_result($stmtAcDisponiveis);
                    
                    if (mysqli_num_rows($resultAcDisponiveis) == 0) {
                        $sqlProxReserva = "SELECT MIN(entradaprevista)
                                           FROM reserva
                                           WHERE idacomodacao = ?
                                           AND status != 'c'";
                        $stmtProxReserva = mysqli_prepare($con, $sqlProxReserva);
                        mysqli_stmt_bind_param($stmtProxReserva, "i", $idacomodacao);
                        mysqli_stmt_execute($stmtProxReserva);
                        $resultProxReserva = mysqli_stmt_get_result($stmtProxReserva);
                        $rowProxReserva = mysqli_fetch_array($resultProxReserva);
                        
                        echo "
                            <tr> 
                                <td>" . htmlspecialchars($rowAcomodacao['idacomodacao']) . "</td>
                                <td>" . htmlspecialchars($rowAcomodacao['nome']) . "</td>
                                <td>" . htmlspecialchars($rowAcomodacao['numero']) . "</td>
                                <td>R$ " . converteReal($rowAcomodacao['valor']) . "</td>
                                <td>" . dataBrasil($rowProxReserva[0]) . "</td>
                                <td class='text-center' title='visualizar'>
                                    <a href='../acomodacao/visualizarAcomodacao.php?id=" . htmlspecialchars($rowAcomodacao['idacomodacao']) . "' class='badge-card badge bg-blue1'>
                                        <i class='fa-solid fa-eye'></i>
                                    </a>
                                </td>    
                            </tr>
                        ";
                        
                        mysqli_stmt_close($stmtProxReserva);
                    }
                    
                    mysqli_stmt_close($stmtAcDisponiveis);
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
?>