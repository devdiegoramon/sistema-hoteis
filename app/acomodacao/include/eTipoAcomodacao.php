<?php

if (isset($_POST['nome'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $sqlDelete = "DELETE FROM acomodacao WHERE idacomodacao = {$id}";
    $sqlUpdate = "UPDATE estacionamento SET idacomodacao = 0";
    if ((mysqli_query($con, $sqlDelete)) AND (mysqli_query($con, $sqlUpdate))) {
        echo "Acomodação excluida com sucesso";
        //GERA LOG
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $date = date("Y-m-d"); //FORMATO AMERICANO
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>excluiu</b> a acomodação com nome: <b>$nome</b>, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                          {$idFuncionario},
                                          'deletar',
                                          '{$descricaoLog}',
                                          '$date',
                                          '$hora')";
        if (mysqli_query($con, $sqlLog)) {

        }
        //FIM LOG
        mysqli_close($con);
    } else {
        echo "Erro ao excluir";
    }
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}