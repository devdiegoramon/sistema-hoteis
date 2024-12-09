<?php

if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";

    $idacomodacao = $_POST['id'];
    $nome = $_POST['nome'];
    $numero = $_POST['numero'];
    $idtipo = $_POST['tipo'];
    $cor = $_POST['cor'];
    $capacidade = $_POST['capacidade'];
    $descricao = $_POST['descricao'];
    if (empty($descricao)) {
        $descricao = 'Sem descrição';
    }

    $valor = $_POST['valor'];
    $valor = str_replace(".", "", $valor); //FORMATA O VALOR PARA CADASTRAR NO BANCO
    $valor = str_replace(",", ".", $valor);
    $valor = !empty($_POST['valor']) ? $valor : 'null';
    $sql = "UPDATE acomodacao SET idtipoacomodacao = '{$idtipo}',
        
                                  nome = '{$nome}',
                                  numero = {$numero},
                                  valor = {$valor},
                                  capacidade = {$capacidade},
                                  descricao = '{$descricao}',
                                  cor = '{$cor}'
            WHERE idacomodacao = {$idacomodacao}";
    if (mysqli_query($con, $sql)) {
        if (mysqli_affected_rows($con)) { //VERIFICA SE HOUVE ALTERAÇÕES 
            //GERA LOG
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>editou</b> a acomodação: <b>$nome</b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'edição',
                                              '{$descricaoLog}',
                                              'acomodacao',
                                              {$idacomodacao},
                                              null,
                                              '$date',
                                              '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
            //FIM LOG
        }
        $text = "Dados alterados com sucesso";
        $type = 0;
    } else {
        $text = "Nenhuma alteração realizada";
        $type = 2;
    }
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}