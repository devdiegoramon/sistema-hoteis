<?php

if (isset($_POST['status'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $status = $_POST['status'];
    $id = $_POST['id'];
    $numero = $_POST['numero'];
    if ($status == 's') {
        $status = 'n';
        $acao = "Desativou";
    } else {
        $status = 's';
        $acao = "Ativou";
    }
    $sql = "UPDATE estacionamento SET ativo = '{$status}' WHERE idvaga = {$id}";
    if (mysqli_query($con, $sql)) {
        echo "Dados atualizados com sucesso";
        //GERA LOG
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $date = date("Y-m-d"); //FORMATO AMERICANO 
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>$acao</b> a vaga de número: <b>$numero</b>, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                          {$idFuncionario},
                                          'edição',
                                          '{$descricaoLog}',
                                          'estacionamento',
                                          {$id},
                                           null,    
                                          '$date',
                                          '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        //FIM LOG
        mysqli_close($con);
    } else {
        echo "Erro ao atualizar";
    }
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}