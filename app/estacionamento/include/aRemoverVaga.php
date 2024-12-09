<?php

if (isset($_GET['idvaga'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $id = $_GET['idvaga'];
    $numero = $_GET['numero'];
    
    $sqlDelete = "DELETE from estacionamento WHERE idvaga = {$id}";
    if (mysqli_query($con, $sqlDelete)) {
        $data = date("Y-m-d");
        $hora = date("H:i:s");
        
        //GERA LOG CADASTRO DE ESTACIONAMENTOSSSSSS
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $descricaoLog = "Funcionário: <b>$funcionario</b>, excluiu uma vaga de estacionamento de número: <b>$numero</b>, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'cadastro',
                                              '{$descricaoLog}',
                                              'estacionamento',
                                              '{$idacomodacao}',
                                              null,    
                                              '$data',
                                              '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        //FIM LOG
        $text = "Vaga excluida com sucesso";
        $type = 0;
    } else {
        $text = "Erro ao excluir vaga de estacionamento";
        $type = 1;
    }

    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}