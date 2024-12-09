<?php

if (!isset($_POST['validar'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    exit();
}
include "../../config/connMysql.php";
include "../../config/config.php";

/* Requisições POST */
$cnpj = $_POST['cnpj'];
$razaoSocial = $_POST['razaosocial'];
$site = $_POST['site'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$celular = $_POST['celular'];

/* Verificando se é um e-mail válido */
if (!empty(trim($email)) AND !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $text = "Informe um email válido";
    $type = 2;
    header("Location: ../index.php?text=$text&type=$type");
    exit; // Para a execução do script
}

/* Deletando dados anteriores */
$sqlDeleteDados = "delete from estabelecimento";
if (mysqli_query($con, $sqlDeleteDados)) {

    /* Cadastrando novos dados */
    $sqlInsertDados = "insert into estabelecimento values (null,
                                                           '$cnpj',
                                                           '$razaoSocial',
                                                           '$site',
                                                           '$email',
                                                           '$telefone',
                                                           '$celular')";
    
    if (mysqli_query($con, $sqlInsertDados)) {
        $text = "Dados alterados com sucesso.";
        $type = 0;

        //GERA LOG
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $date = date("Y-m-d"); //FORMATO AMERICANO 
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $descricaoLog = "Funcionário: <b>$funcionario</b>, alterou os dados da hospedagem, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                            {$idFuncionario},
                                            'cadastro',
                                            '{$descricaoLog}',
                                            'estabelecimento',
                                            {$idcategoria},
                                            null,    
                                            '$date',
                                            '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        
    } else {
        $text = "Erro ao alterar os dados da hospedagem.";
        $type = 1;
    }
} else {
    $text = "Erro ao alterar os dados da hospedagem.";
    $type = 1;
}

mysqli_close($con);
header("Location: ../index.php?text=$text&type=$type");
