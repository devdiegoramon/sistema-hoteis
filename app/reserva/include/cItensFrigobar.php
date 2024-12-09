<?php
if (isset($_POST['idfrigobar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/components.php";
    include "../../include/func.php";
    $idfrigobar = $_POST['idfrigobar'];
    $sql = "SELECT i.iditemfrigobar,
                   e.item
            FROM itemfrigobar i
            INNER JOIN estoque e ON (i.iditem = e.iditem) 
            WHERE i.idfrigobar = {$idfrigobar}
            AND i.ativo = 's'
            AND i.quantidade > 0";
    $result = mysqli_query($con, $sql);
    $num = mysqli_num_rows($result);
    if ($num > 0) {
        echo "<option disabled selected value=''> Selecione uma opção </option>";
        while ($row = mysqli_fetch_array($result)) {
            ?>
            <option value="<?= $row[0] ?>">  <?= $row[1] ?> </option>
            <?php
        }
    } else {
        echo "<option disabled selected> Nenhum item disponível </option>";
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}
