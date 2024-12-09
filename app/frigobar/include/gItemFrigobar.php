<?php

if (isset($_POST['adicionar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $idfrigobar = $_POST['idfrigobar'];
    $categoria = $_POST['categoria'];
    $iditem = $_POST['item'];
    $quantidade = $_POST['quantidade']; //QUANTIDADE A SER ADICIONADA AO FRIGOBAR
    $estoqueNovo = $_POST['estoque']; //QUANTIDADE FUTURA DO ESTOQUE DO ITEM
    $estoqueAnterior = $quantidade + $estoqueNovo;

    //ATUALIZA A QUANTIDADE DE ITENS DISPONIVEIS
    $sqlEstoque = "UPDATE estoque SET quantidade = $estoqueNovo
                   WHERE iditem = $iditem";

    if (mysqli_query($con, $sqlEstoque)) {
        //CONFERE SE O ITEM JÁ EXISTE (SE SIM, VAI REALIZAR APENAS UM UPDATE)
        $sqlConfere = "SELECT quantidade, iditemfrigobar FROM itemfrigobar WHERE iditem = $iditem AND idfrigobar = {$idfrigobar}";
        $resultConfere = mysqli_query($con, $sqlConfere);

        if (mysqli_num_rows($resultConfere) > 0) {
            $iditemfrigobar = $rowConfere[1];
            
            $rowConfere = mysqli_fetch_array($resultConfere);
            $novaQuantidade = $rowConfere[0] + $quantidade;
            $sqlUpdate = "UPDATE itemfrigobar SET quantidade = {$novaQuantidade} WHERE iditem = {$iditem} AND idfrigobar = {$idfrigobar}";
            if (mysqli_query($con, $sqlUpdate)) {
                $text = "Itens adicionados com sucesso";
                $type = 0;
            }
        } else {
            $sqlAdicionar = "INSERT INTO itemfrigobar VALUES(null,  
                                                    $idfrigobar,
                                                    $iditem,
                                                    $quantidade,
                                                    's')";
            if (mysqli_query($con, $sqlAdicionar)) {
                $iditemfrigobar = mysqli_insert_id($con);
                $text = "Itens adicionados com sucesso";
                $type = 0;
            }
        }

        //GERA LOG
        $date = date("Y-m-d"); //FORMATO AMERICANO 
        $data = date("d/m/Y");
        $hora = date("H:i:s");

        $sqlItem = "SELECT item FROM estoque WHERE iditem = $iditem";
        $resultItem = mysqli_query($con, $sqlItem);
        $rowItem = mysqli_fetch_array($resultItem);

        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $descricaoLog = "Funcionário: <b>$funcionario</b>, adicionou $quantidade $rowItem[0]  ao frigobar Nº$idfrigobar, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                            {$idFuncionario},
                                            'adicionar',
                                            '{$descricaoLog}',
                                            'itemfrigobar',
                                            {$iditemfrigobar},
                                            null,    
                                            '$date',
                                            '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
    } else {
        $text = "Erro ao adicionar";
        $type = 1;
    }
    header("Location: ../frigobar.php?idfrigobar=$idfrigobar&text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

