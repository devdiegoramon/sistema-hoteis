<?php

if (isset($_POST['nome'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";

    $idcategoria = $_POST['id'];
    $nome = $_POST['nome'];
    $novoNome = $_POST['input'];
    $sql = "UPDATE categoriaestoque SET nome = '{$novoNome}' WHERE idcategoria = {$idcategoria}";
    if (mysqli_query($con, $sql)) {
        if (mysqli_affected_rows($con)) { //VERIFICA SE HOUVE ALTERAÇÕES 
            //GERA LOG
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $date = date("Y-m-d"); //FORMATO AMERICANO 
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $descricaoLog = "Funcionário: <b>$funcionario</b>, <b>editou</b> a categoria de: <b>$nome</b> para <b> $novoNome </b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'edição',
                                              '{$descricaoLog}',
                                              'categoriaestoque',
                                              {$idcategoria},
                                              null,    
                                              '$date',
                                              '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
            //FIM LOG
            mysqli_close($con);
            echo '<span class="border border-success text-success badge mb-3"><i class="fa-solid fa-circle-check"></i> Dados alterados com sucesso.</span>';
        }
    } else {
         echo '<span class="border border-danger text-danger badge mb-3"><i class="fa-solid fa-circle-xmark"></i> Erro ao editar categoria.</span>';
    }
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}