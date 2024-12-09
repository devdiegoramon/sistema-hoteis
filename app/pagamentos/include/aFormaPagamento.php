<?php

include "../../config/config.php";
if (isset($_POST['nome'])) {
    include "../../config/connMysql.php";

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $novoNome = $_POST['input'];
    $sql = "UPDATE formapagamento SET nome = '{$novoNome}' WHERE idformapagamento = {$id}";
    if (mysqli_query($con, $sql)) {
        if (mysqli_affected_rows($con)) { //VERIFICA SE HOUVE ALTERAÇÕES 
            //GERA LOG
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>editou</b> a formas de: <b>$nome</b> para <b> $novoNome </b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'edição',
                                              '{$descricaoLog}',
                                              'formapagamento',
                                              {$id},
                                              null,
                                              '$date',
                                              '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
           
            }
            //FIM LOG
            echo '<span class="border border-success text-success badge mb-3"><i class="fa-solid fa-circle-check"></i> Dados alterados com sucesso</span>';
        } else {
            echo '<span class="border border-warning text-black badge mb-3"><i class="fa-solid fa-circle-exclamation"></i></i> Nenhuma alteração realizada</span>';
        }
    } else {
        echo '<span class="border border-warning text-black badge mb-3"><i class="fa-solid fa-circle-exclamation"></i> Nenhuma alteração realizada</span>';
    }

    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}