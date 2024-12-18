<?php
include "../../config/config.php";

if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";

    // Set the correct timezone
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtual = date("Y-m-d");

    // Prepare the SQL statement
    $sqlCheckinHj = "SELECT r.idreserva,
                        r.entradaprevista,
                        r.saidaprevista,
                        r.status,
                        a.nome AS acomodacao_nome,
                        c.nome AS cliente_nome
                 FROM reserva r       
                 INNER JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                 INNER JOIN cliente c ON (r.idcliente = c.idcliente)
                 WHERE r.status = 'p'
                 AND DATE(r.entradaprevista) <= ?";

    if ($stmt = mysqli_prepare($con, $sqlCheckinHj)) {
        mysqli_stmt_bind_param($stmt, "s", $dataAtual);
        mysqli_stmt_execute($stmt);
        $resultCheckinHj = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultCheckinHj) > 0) {
            ?>
            <div class="table-responsive col-lg-12"> 
                <table style="border-radius:10px;" class="table-card table table-hover table-secondary table-striped" id="datatable"> 
                    <thead style="border-radius:3em;"> 
                        <tr> 
                            <th>ID</th>
                            <th>Acomodação</th>
                            <th>Cliente</th>
                            <th>Entrada prevista</th>
                            <th>Saída prevista</th>
                            <th>Situação</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($rowCheckinHj = mysqli_fetch_assoc($resultCheckinHj)) {
                            echo "<tr>"; 
                            echo "<td>" . htmlspecialchars($rowCheckinHj['idreserva']) . "</td>";
                            echo "<td>" . htmlspecialchars($rowCheckinHj['acomodacao_nome']) . "</td>";
                            echo "<td>" . htmlspecialchars($rowCheckinHj['cliente_nome']) . "</td>";
                            echo "<td>" . dataBrasil($rowCheckinHj['entradaprevista']) . "</td>";
                            echo "<td>" . dataBrasil($rowCheckinHj['saidaprevista']) . "</td>";
                            echo "<td>" . statusReserva($rowCheckinHj['status'], 1) . "</td>";
                            echo "<td class='text-center' title='visualizar'>";
                            echo "<a href='../reserva/visualizarReserva.php?id=" . htmlspecialchars($rowCheckinHj['idreserva']) . "' class='badge-card badge bg-blue1'>";
                            echo "<i class='fa-solid fa-eye'></i>";
                            echo "</a>";
                            echo "</td>";    
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            echo "<p>Não há reservas para check-in hoje.</p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Erro na preparação da consulta: " . mysqli_error($con);
    }

    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    exit();
}
?>