<?php

if (isset($_POST['id'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $iditem = $_POST['id'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $valor = $_POST['valor'];
    $valor = str_replace(".", "", $valor); //FORMATA O VALOR PARA CADASTRAR NO BANCO
    $valor = str_replace(",", ".", $valor);

    $sqlConfere = "SELECT 1 FROM estoque WHERE item = '{$nome}' and iditem != {$iditem}";
    $resultConfere = mysqli_query($con, $sqlConfere);
    if (mysqli_num_rows($resultConfere) == 0) {


        $sql = "UPDATE estoque SET item = '{$nome}',
                                   categoria = '{$categoria}',
                                   valorunitario = '{$valor}'    
                 WHERE iditem = {$iditem}";
        if (mysqli_query($con, $sql)) {
            if (mysqli_affected_rows($con)) {
                echo "Dados atualizados com sucesso";
                //GERA LOG
                $idFuncionario = $_SESSION['idlogin'];
                $funcionario = $_SESSION['login'];
                $date = date("Y-m-d"); //FORMATO AMERICANO 
                $data = date("d/m/Y");
                $hora = date("H:i:s");
                $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>editou</b> o item Nº$iditem: <b>$nome</b>, no dia <b>$data</b> às <b>$hora</b>";
                $sqlLog = "INSERT INTO log VALUES (null,
                                          {$idFuncionario},
                                          'edição',
                                          '{$descricaoLog}',
                                          'estoque',
                                          '{$iditem}',
                                          null,
                                          '$date',
                                          '$hora')";
                if (mysqli_query($con, $sqlLog)) {
                    
                }
                //FIM LOG
                mysqli_close($con);
            }
        } else {
            echo "Erro";
        }
    } else {
        echo "Nome cadastrado anteriormente";
    }
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}    