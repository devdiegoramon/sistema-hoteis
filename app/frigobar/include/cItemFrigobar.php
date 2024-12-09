<?php
if (isset($_POST['id'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/components.php";
    include "../../include/func.php";
    $idfrigobar = $_POST['id'];
    $frigobar = $_POST['frigobar'];
    $sql = "SELECT i.iditemfrigobar,
                   i.iditem,
                   i.quantidade,
                   e.item,
                   e.categoria,
                   e.valorunitario,
                   i.ativo
            FROM itemfrigobar i
            INNER JOIN estoque e ON (i.iditem = e.iditem) 
            WHERE i.idfrigobar = {$idfrigobar}";
    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_array($result)) {
        if ($row[6] == 's') {
            $btn = $btnAtivo;
        } else {
            $btn = $btnInativo;
        }
        ?>
        <tr> 
            <td> <?= $row[0] ?> </td>
            <td> <?= $row[3] ?> </td>
            <td> <?= $row[4] ?> </td>
            <td> <?= $row[2] ?> </td>
            <td> R$ <?= converteReal($row[5]) ?> </td>
            <td class="text-center"> 
                <?php if ($_SESSION['nivel'] == 1) { ?>
                    <a onclick="statusItemFrigobar(<?= "$idfrigobar,'$row[6]', '$row[3]','$frigobar', '$row[0]'" ?>)" style="cursor:pointer;"><?= $btn ?> </a>   
                <?php
                } else {
                    echo "<a>$btn</a";
                }
                ?>
            </td>
            <td class="text-center"> 
                <a onclick="devolverItem(<?php echo "$row[0], $idfrigobar, '$frigobar'" ?>)" class="badge bg-secondary btn-dark" style="cursor:pointer;"  data-html="true" data-toggle="tooltip" data-placement="top" title="Devolver ao estoque"><i class="fa fa-reply" aria-hidden="true"></i></a> 
            </td>
        </tr>   
        <?php
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}
