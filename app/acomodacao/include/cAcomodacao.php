<?php
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";
    include "../../include/components.php";
    $sql = "SELECT a.idacomodacao,
                   a.nome,
                   a.numero,
                   t.nome,
                   a.valor,
                   a.capacidade,
                   a.descricao,
                   a.ativo
            FROM acomodacao a
            INNER JOIN tipoacomodacao t on (t.idtipoac = a.idtipoacomodacao)";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        if ($row[7] == 's') {
            $btn = $btnAtivo;
        } else {
            $btn = $btnInativo;
        }
        ?>
        <tr> 
            <td> <?= $row[0] ?></td>
            <td> <?= $row[1] ?></td>
            <td class="width-0"> <?= $row[2] ?></td>
            <td class="width-0"> <?= $row[3] ?></td>
            <td class="width-0"> <nobr>  <?= $row[4] ? "R$ ".converteReal($row[4]) : "" ?> </nobr></td>
            <td class="width-0"> <?= $row[5] ?></td>
            <td> <?= $row[6] ?></td>
            <?php
            if ($_SESSION['nivel'] == 1) {
                ?>
                <td class="width-0"><a title="Status" onclick="statusAcomodacao(<?= "$row[0],'$row[7]', '$row[1]'" ?>)" style="cursor:pointer;"> <?= $btn ?></a></td>
                <?php
            } else {
                echo "<td class='width-0'><a> $btn </a></td>";
            }
            ?>
            <td class="text-center">
                <a class="btn-acao bg-yellow badge text-black" onclick="modalEditarAcomodacao(<?= "$row[0]" ?>)" title="Editar"> <i class="fa-solid fa-pencil"></i> </a>
                <a class="btn-acao bg-blue badge text-black" href="visualizarAcomodacao.php?id=<?= $row[0] ?>" title="Visualizar"> <i class="fa-solid fa-eye"></i> </a>
            </td>
        </tr>
        <?php
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}


