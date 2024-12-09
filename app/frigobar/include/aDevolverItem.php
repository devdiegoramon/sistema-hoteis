<?php

if (isset($_POST['iditem'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $iditem = $_POST['iditem'];
    $idfrigobar = $_POST['idfrigobar'];

    //RECUPERA OS DADOS DO ITEM ANTES DE EXCLUIR DO FRIGOBAR
    $sqlDados = "SELECT i.quantidade, 
                        e.item,
                        e.quantidade,
                        e.iditem
                FROM itemfrigobar i
                INNER JOIN estoque e ON (i.iditem = e.iditem)
                WHERE i.iditemfrigobar = {$iditem}";
    $resultDados = mysqli_query($con, $sqlDados);
    $rowDados = mysqli_fetch_array($resultDados);

    //EXCLUI O ITEM DO FRIGOBAR
    $sqlDelete = "DELETE FROM itemfrigobar WHERE iditemfrigobar = {$iditem}";
    if (mysqli_query($con, $sqlDelete)) {
        //ATUALIZA A QUANTIDADE TOTAL EM ESTOQUE (QTD SAIDA DO FRIGOBAR + QTD EM ESTOQUE)
        $quantidadeRecuperada = $rowDados[0] + $rowDados[2];
        $sqlUpdateEstoque = "UPDATE estoque SET quantidade = {$quantidadeRecuperada} WHERE iditem = $rowDados[3]";
        if (mysqli_query($con, $sqlUpdateEstoque)) {

            //GERA LOG
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>devolveu</b> $rowDados[0] $rowDados[1] de dentro do frigobar Nº$idfrigobar, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                          {$idFuncionario},
                                          'devolução',
                                          '{$descricaoLog}',
                                          'itemfrigobar',
                                          {$iditem},
                                          null,    
                                          '$date',
                                          '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
            //FIM LOG
            echo "Devolução realizada";
            mysqli_close($con);
        } else {
            echo "Erro";
        }
    } else {
        echo "Erro";
    }
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}