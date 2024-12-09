<?php

include "../../config/config.php";
if (isset($_POST['salvar'])) {
    include "../../config/connMysql.php";
    $iditem = $_POST['id'];
    $quantItens = $_POST['quant-item'];
    $quantidade = $_POST['quantidade'];
    $adicionar = $_POST['adicionar'];
    $futura = $_POST['futura'];

    $i = 0;
    for ($i; $i < $quantItens; $i++) {
        if ($adicionar[$i] > 0 || $adicionar[$i] != '') {
            $sqlEntrada = "UPDATE estoque 
                      SET quantidade = {$futura[$i]}
                      WHERE iditem = {$iditem[$i]}";

            if (mysqli_query($con, $sqlEntrada)) {
                //REGISTRANDO MOVIMENTAÇÃO DO ESTOQUE
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $funcionario = $_SESSION['login'];
                $sqlMov = "INSERT INTO movestoque VALUES (null,
                                                     {$iditem[$i]},
                                                     'entrada',
                                                     {$adicionar[$i]},
                                                     '{$funcionario}',
                                                     '',
                                                     '{$date}',
                                                     '{$time}')";

                if (mysqli_query($con, $sqlMov)) {
                    //FIM MOVIMENTAÇÃO
                }
                $text = "Entrada realizada com sucesso";
                $type = 0;
            } else {
                $text = "Erro ao realizar a entrada";
                $type = 1;
            }
        }
    }
 
     $_SESSION['tipoEstoque'] = 'entrada';
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}