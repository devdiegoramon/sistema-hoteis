<?php

if (isset($_POST['id'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $idfrigobar = $_POST['id'];
    $modelo = $_POST['modelo'];
    $patrimonio = $_POST['patrimonio'];
    $idacomodacao = $_POST['acomodacao'];

    if (empty($patrimonio)) {
        $patrimonio = "S/N";
    }

    $sql = "UPDATE frigobar SET idacomodacao = {$idacomodacao},
                                modelo = '{$modelo}',
                                patrimonio = '{$patrimonio}'
            WHERE idfrigobar = {$idfrigobar}";
    if (mysqli_query($con, $sql)) {
        if (mysqli_affected_rows($con)) { //VERIFICA SE HOUVE ALTERAÇÕES 
            //GERA LOG
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>editou</b> o frigobar <b>Nº" . $idfrigobar . "</b> : Patrimônio: <b>$patrimonio</b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'edição',
                                              '{$descricaoLog}',
                                              'frigobar',
                                              {$idfrigobar},
                                              null,    
                                              '$date',
                                              '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
            //FIM LOG
            echo "Dados alterados com sucesso";
        }
    } else {
        echo "Erro";
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}