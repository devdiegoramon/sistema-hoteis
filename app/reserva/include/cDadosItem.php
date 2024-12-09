<?php

//VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['iditem'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $iditem = $_POST['iditem'];
    $dados = [];
    
    $sqlDadosItem = "SELECT i.iditemfrigobar,
                            i.iditem,
                            i.quantidade,
                            e.item,
                            e.valorunitario
                    FROM itemfrigobar i
                    INNER JOIN estoque e ON (i.iditem = e.iditem)
                    WHERE i.iditemfrigobar = {$iditem}";
    $resultDadosItem = mysqli_query($con, $sqlDadosItem);
    $rowDadosItem = mysqli_fetch_array($resultDadosItem);

    $valorunitario = $rowDadosItem[4];
    $quantidadMax = $rowDadosItem[2];
    $dados = (['valorunitario' => $valorunitario, 'quantidadeMax' => $quantidadMax]);

    echo json_encode($dados);
    
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}