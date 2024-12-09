<?php

if (isset($_POST['status'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    
    $status = $_POST['status'];
    $idfuncionario = $_POST['idfuncionario'];
    $nome = $_POST['nome'];
    
    if ($status == 's') {
        $status = 'n';
        $acao = "Desativou";
        $acaoLog = "desativar";
    } else {
        $status = 's';
        $acao = "Ativou";
        $acaoLog = "ativar";
    }
    $sql = "UPDATE funcionario SET ativo = '{$status}' WHERE idlogin = {$idfuncionario}";
    if (mysqli_query($con, $sql)) {

        //GERA LOG
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $date = date("Y-m-d"); //FORMATO AMERICANO 
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>$acao</b> o usuário: <b>$nome</b>, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                          {$idFuncionario},
                                          '{$acaoLog}',
                                          '{$descricaoLog}',
                                          'funcionario',
                                          {$id},
                                          null,
                                          '$date',
                                          '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        //FIM LOG
        mysqli_close($con);
    }
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}