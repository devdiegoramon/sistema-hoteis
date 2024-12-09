<?php
if (isset($_POST['seguranca'])) {
    include "../../config/config.php";
    include "../../config/connMysql.php";
    include "../../include/func.php";
    include "../../include/components.php";

    $sqlFuncionario = "SELECT idlogin,
                            nome,
                            login,
                            nivel,
                            ativo,
                            cpf
                     FROM funcionario";
    $resultFuncionario = mysqli_query($con, $sqlFuncionario);
    while ($rowFuncionario = mysqli_fetch_array($resultFuncionario)) {
        if ($rowFuncionario[3] == 1) {
            $tipo = "Administrador";
        } else {
            $tipo = "Funcionário";
        }
        if ($rowFuncionario[4] == 's') {
            $btn = $btnAtivo;
        } else {
            $btn = $btnInativo;
        }
        ?>
        <tr> 
            <td> <?= $rowFuncionario[0] ?> </td>
            <td> <?= $rowFuncionario[1] ?> </td>
            <td> <nobr>  <?= $rowFuncionario[5] ?> </nobr></td>
        <td> <?= $tipo ?> </td>
        <td class="text-center"> <a class="btn-status" onclick="statusFuncionario(<?php echo "$rowFuncionario[0], '$rowFuncionario[4]', ' $rowFuncionario[2]'" ?>)"> <?= $btn ?> </a></td>
        <td class="text-center"> <a class="btn-status badge bg-yellow text-black" onclick="modalEditarFuncionario(<?= $rowFuncionario[0] ?>)"> <i class="fa-solid fa-pencil"></i> </a></td>
        </tr>
        <?php
    }

    mysqli_close($con);
} else {
    $text = "Sem acesso";
    $type = 1;
    header("Location: ../../../index.php?text=$text&type=$type");
}



