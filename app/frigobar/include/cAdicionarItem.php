<?php

if (isset($_POST['categoria'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $categoria = $_POST['categoria'];
    if ($categoria == 'todas') {
        $condicao = "";
    } else {
        $condicao = "WHERE categoria = '$categoria'";
    }
    $sql = "SELECT iditem, item, quantidade FROM estoque $condicao";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) == 0) {
        echo '<option value=""> Nenhum item cadastrado </option>';
    } else {
        echo '<option value=""> Selecione uma opção </option>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row[0] . '"> ' . $row[1] . ' </option>';
        }
    }
    mysqli_close($con);
}