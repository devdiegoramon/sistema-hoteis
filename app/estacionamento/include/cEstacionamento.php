<?php
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";
    include "../../include/components.php";
    $sqlVaga = "SELECT * FROM estacionamento";
    $resultVaga = mysqli_query($con, $sqlVaga);
    while ($row = mysqli_fetch_array($resultVaga)) {
        if ($row[1] == null OR $row[1] == '') {
            $idacomodacao = '0';
        } else {
            $idacomodacao = $row[1];
        }

        $sqlAcomodacao = "SELECT nome FROM acomodacao WHERE idacomodacao = $idacomodacao";
        $resultAcomodacao = mysqli_query($con, $sqlAcomodacao);

        if (mysqli_num_rows($resultAcomodacao) > 0) {
            $rowAcomodacao = mysqli_fetch_array($resultAcomodacao);
            $acomodacao = $rowAcomodacao[0];
        } else {
            $acomodacao = "Disponível";
        }

        if ($row[3] == 's') {
            $btn = $btnAtivo;
        } else {
            $btn = $btnInativo;
        }
        ?>
        <tr> 
            <td> <?= $row[0] ?></td>
            <td> <?= $row[2] ?></td>
            <td> <?= $acomodacao ?></td>
            
            <?php
            if ($_SESSION['nivel'] == '1') {
                ?>
                <td class='text-center'><a onclick="statusEstacionamento(<?= "$row[0],'$row[3]', '$row[2]'" ?>)" style="cursor:pointer;"> <?= $btn ?> </a></td>
                <td class="text-center"> <a href='include/aRemoverVaga.php?idvaga=<?= $row[0] ?>&numero=<?= $row[2] ?>' title="remover" style="cursor: pointer" class="badge bg-danger"> X </a>  </td>
                <?php
            } else {
                echo "<td><a> $btn </a></td>";
            }
            ?>
        </tr>
        <?php
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

