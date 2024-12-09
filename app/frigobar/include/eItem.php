<?php

//PAGINA NÃO CONCLUIDADA (Não será necessário)
if (isset($_POST['id'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $iditem = $_POST['iditem'];
    $nomeitem = $_POST['nomeitem'];
    $idfrigobar = $_POST['idfrigobar'];

    $sqlDelete = "DELETE from itemfrigobar WHERE iditemfrigobar = {$iditem}";
    if (mysqli_query($con, $sqlDelete)) {

        //GERA LOG
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $date = date("Y-m-d"); //FORMATO AMERICANO 
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>excluiu</b> o item <b>Nº" . $iditem . "</b>: <b>$nomeitem</b>, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'deletar item',
                                              '{$descricaoLog}',
                                              '$date',
                                              '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        //FIM LOG
        $text = "Frigobar <b>cadastrado</b> com sucesso";
        $type = 2;
    }
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}