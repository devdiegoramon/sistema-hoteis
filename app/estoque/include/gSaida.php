<?php

include "../../config/config.php";
if (isset($_POST['salvar'])) {
    include "../../config/connMysql.php";
    $iditem = $_POST['id'];
    $quantItens = $_POST['quant-item'];
    $quantidade = $_POST['quantidade'];
    $retirar = $_POST['retirar'];
    $futura = $_POST['futura'];
    $motivo = $_POST['motivo'];

    $i = 0;
    for ($i; $i < $quantItens; $i++) {
        if ($retirar[$i] > 0 || $retirar[$i] != '') {
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
                                                     'saida',
                                                     {$retirar[$i]},
                                                     '{$funcionario}',
                                                     '$motivo[$i]',
                                                     '{$date}',
                                                     '{$time}')";

                if (mysqli_query($con, $sqlMov)) {
                    //FIM MOVIMENTAÇÃO
                }
                $text = "Saida realizada com sucesso";
                $type = 0;
            } else {
                $text = "Erro ao realizar a saida";
                $type = 1;
            }
        }
    }

    $_SESSION['tipoEstoque'] = 'saida';
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}