<?php

if (isset($_POST['iditem'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $iditem = $_POST['iditem'];
    $sql = "SELECT quantidade FROM estoque WHERE iditem = {$iditem}";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    echo $row[0];
    
    mysqli_close($con);
}