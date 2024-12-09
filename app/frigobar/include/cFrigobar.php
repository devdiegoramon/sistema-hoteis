<?php
if (isset($_POST['seguranca'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/components.php";
    $sql = "SELECT f.idfrigobar,
                   f.modelo,
                   f.patrimonio,
                   f.ativo,
                   a.nome,
                   f.idacomodacao
            FROM frigobar f
            INNER JOIN acomodacao a on (a.idacomodacao = f.idacomodacao)";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        if ($row[3] == 's') {
            $btn = $btnAtivo;
        } else {
            $btn = $btnInativo;
        }
        ?>
        <tr> 
            <td> <?= $row[0] ?></td>
            <!--modelo -->
            <td> <input class='border-0' style="padding: 3px 4px" disabled value="<?= $row[1] ?>" type="text" id="modeloFrig<?= $row[0] ?>"></td>
            <!--patrimonio-->
            <td> <input class='border-0' style="padding: 3px 4px" disabled value="<?= $row[2] ?>" type="text" id="patrimonioFrig<?= $row[0] ?>"></td>
            <!--Acomodação-->
            <td id="inputAc<?= $row[0] ?>"> 
                <div id="ac"> <?= $row[4] ?> </div>
                <div id="select-acomodacao<?= $row[0] ?>" hidden> 
                    <select class='border-0' id="acomodacaoFrig<?= $row[0] ?>" style="height: 27px; margin-left: -2px;">
                        <option value="<?= $row[5] ?>" selected> <?= $row[4] ?> </option> 
                        <?php
                        $sqlConsultaCat = "SELECT idacomodacao ,nome FROM acomodacao WHERE ativo = 's' AND idacomodacao != '$row[5]'";
                        $resultConsultaCat = mysqli_query($con, $sqlConsultaCat);
                        while ($rowCat = mysqli_fetch_array($resultConsultaCat)) {
                            echo '<option value="' . $rowCat[0] . '" class="form-option">' . $rowCat[1] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </td>
            <td class='text-center'>
                <?php if ($_SESSION['nivel'] == 1) { ?>
                    <a onclick="statusFrigobar(<?= "$row[0],'$row[3]', '$row[2]'" ?>)" style="cursor:pointer;"> <?= $btn ?></a></td>
                <?php
            } else {
                echo "<a> $btn </a>";
            }
            ?>
        </td>
        <td class="text-center">
            <a class="btn-acao bg-yellow badge text-black"  id="icon-editar<?= $row[0] ?>" onclick="editarFrigobar(<?= "$row[0]" ?>)">  <i class="fa-solid fa-pencil"></i>  </a>
            <a class="btn-acao bg-blue1 badge text-black" href="frigobar.php?idfrigobar=<?= $row[0] ?>" >  <i class="fa-solid fa-eye"></i></a></td>
        </tr>
        <?php
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

    