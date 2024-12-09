<?php

if (!isset($_POST['validar'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    exit();
}
include "../../config/connMysql.php";
include "../../config/config.php";

/* Requisições POST */
$cep = $_POST['cep'];
$logradouro = $_POST['logradouro'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$cidade = $_POST['cidade'];
$bairro = $_POST['bairro'];

/* Deletando dados anteriores */
$sqlDeleteDados = "delete from enderecoestabelecimento";
if (mysqli_query($con, $sqlDeleteDados)) {

    /* Cadastrando novos dados */
    $sqlInsertDados = "insert into enderecoestabelecimento values (null,
                                                                    '$logradouro',
                                                                    '$numero',
                                                                    '$complemento',
                                                                    '$cidade',
                                                                    '$bairro',
                                                                    '$cep')";

    if (mysqli_query($con, $sqlInsertDados)) {
        $text = "Endereço alterado com sucesso.";
        $type = 0;

        //GERA LOG
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $date = date("Y-m-d"); //FORMATO AMERICANO 
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $descricaoLog = "Funcionário: <b>$funcionario</b>, alterou os dados do endereço da hospedagem, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                            {$idFuncionario},
                                            'cadastro',
                                            '{$descricaoLog}',
                                            'enderecoestabelecimento',
                                            {$idcategoria},
                                            null,    
                                            '$date',
                                            '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
    } else {
        $text = "Erro ao alterar endereço.";
        $type = 1;
    }
} else {
    $text = "Erro ao alterar endereço.";
    $type = 1;
}

mysqli_close($con);
header("Location: ../index.php?text=$text&type=$type");
