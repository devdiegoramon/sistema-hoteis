<?php
    include "../../config/config.php";
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/components.php";
    $sql = "SELECT * FROM categoriaestoque order by nome";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        if ($row[2] == 's') {
            $btn = $btnAtivo;
        } else {
            $btn = $btnInativo;
        }
        ?>
        <tr> 
            <td> <?= $row[0] ?></td>
            <td><input id="input-nomeCategoria<?= $row[0] ?>" class="form-control form-control-sm w-100" disabled value="<?= $row[1] ?>"></td>
            <td>
                <?php if ($_SESSION['nivel'] == 1) { ?>
                    <a onclick="statusCategoria(<?= "$row[0],'$row[2]', '$row[1]'" ?>)" style="cursor:pointer;"> <?= $btn ?></a></td>
                <?php
            } else {
                echo "<a> $btn </a>";
            }
            ?>
            <td class="text-center"><a id="icon-categoria<?= $row[0] ?>" onclick="editarCategoria(<?= "$row[0], '$row[1]'" ?>)" style="cursor:pointer;"> <i class="fa fa-pencil" aria-hidden="true"></i> </a></td>
        </tr>
        <?php
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

