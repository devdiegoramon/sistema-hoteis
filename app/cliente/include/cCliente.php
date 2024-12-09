<?php
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";
    include "../../include/components.php";
    $sql = "SELECT idcliente,
                   nome,
                   cpf,
                   dtnasc,
                   email,
                   telefone,
                   estado,
                   cidade,
                   datag,
                   ativo
            FROM cliente order by nome";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        ?>
        <tr> 
            <td class='width-0'> <?= $row[0] ?></td>
            <td> <?= $row[1] ?></td>
            <td> <nobr><?= $row[2] ?></nobr></td>
            <td><nobr> <?= dataBrasil($row[3]) ?></nobr></td>
            <td> <?= $row[4] ?></td>
            <td class='width-0'> <nobr> <?= $row[5] ?> </nobr></td>
            <td class="width-0 text-center">
            <?php
            //VERIFICA SE USUÁRIO È ADMINISTRADOR
            if ($_SESSION['nivel'] == 1) {
                ?> 
                <a onclick="statusCliente(<?= "$row[0],'$row[9]', '$row[1]'" ?>)" style="cursor:pointer;"> <?php echo $row[9] == 's' ? $btnAtivo : $btnInativo ?></a></td>
            <?php
        } else {
            ?>
            <a> <?php echo $row[9] == 's' ? $btnAtivo : $btnInativo ?></a></td>
        <?php }
        ?>
        <td class="width-0 text-center"><a class='badge bg-yellow text-black' onclick="modalEditarCliente(<?= "$row[0]" ?>)" style="cursor:pointer;" title='editar'> <i class="fa fa-pencil" aria-hidden="true"></i> </a></td>
        </tr>
        <?php
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

    