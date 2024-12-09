<?php
    include "../../config/config.php";
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";
    include "../../include/components.php";
    $sql = "SELECT iditem,
                   item,
                   categoria,
                   quantidade,
                   valorunitario,
                   ativo
            FROM estoque";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        if ($row[5] == 's') {
            $btn = $btnAtivo;
        } else {
            $btn = $btnInativo;
        }
        ?>
        <tr> 
            <!--id-->
            <td> <?= $row[0] ?></td>
            <!--nome-->
            <td> <input class="border-0" style="padding: 0 4px" disabled value="<?= $row[1] ?>" type="text" id="nomeItem<?= $row[0] ?>"></td>
            <!--categoria-->
            <td id="input-categoriaItem<?= $row[0] ?>"> <?= $row[2] ?>
                <div hidden id="select-categoria<?= $row[0] ?>"> 
                    <select id="categoria<?= $row[0] ?>" style="height: 27px; padding: 0 4px">
                        <option selected value="<?= $row[2] ?>"> <?= $row[2] ?> </option>
                        <?php
                        $sqlSelectCat = "SELECT nome FROM categoriaestoque WHERE ativo = 's' AND nome != '$row[2]'";
                        $resultSelectCat = mysqli_query($con, $sqlSelectCat);
                        while ($rowCat = mysqli_fetch_array($resultSelectCat)) {
                            echo '<option value="' . $rowCat[0] . '"> ' . $rowCat[0] . ' </option>';
                        }
                        ?>
                    </select>
                </div>
            </td>
            <!--quantidade-->
            <td> <?= $row[3] ?> </td>

            <!--valor-->
            <td><span> R$ </span> <input class="border-0" style="padding: 0 4px" disabled value="<?= converteReal($row[4]) ?>" type="text" id="valorItem<?= $row[0] ?>" class="money"></td>

            <!--ações-->
            <td class="text-center">
                <?php if ($_SESSION['nivel'] == 1) { ?>
                    <a  onclick="statusItem(<?= "$row[0],'$row[5]', '$row[1]'" ?>)" style="cursor:pointer;"> <?= $btn ?></a>
                    <?php
                } else {
                    echo "<a> $btn </a>";
                }
                ?>
            </td>
            <td class="text-center"><a id="icon-editarItem<?= $row[0] ?>" class="badge bg-warning text-black" onclick="editarItem(<?= "$row[0]" ?>)" style="cursor:pointer;"> <i class="fa fa-pencil" aria-hidden="true"></i> </a></td>
        </tr>
        <?php
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

