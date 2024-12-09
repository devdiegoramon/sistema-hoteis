<?php

if (isset($_GET['idreserva'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $login = $_POST['loginadministrador'];
    $senha = $_POST['senha'];
    $idreserva = $_GET['idreserva'];

    $sqlVerifica = "SELECT idlogin,
                           login, 
                           nome,
                           nivel,
                           senha
                    FROM funcionario 
                    WHERE login = '{$login}' 
                    AND ativo = 's' 
                    AND nivel = 1;
                    ";
    $resultVerifica = mysqli_query($con, $sqlVerifica);

    if (mysqli_num_rows($resultVerifica) > 0) {
        $row = mysqli_fetch_array($resultVerifica);
        if (!password_verify($senha, $row[4])) { //VERIFICA O HASH DE SENHA 
            $text = "Usuário ou senha invalidos";
            $type = 1;
            mysqli_close($con);
            header("Location: ../visualizarReserva.php?id=$idreserva&text=$text&type=$type");
            exit();
        }
    } else {
        mysqli_close($con);
        header("Location: ../visualizarReserva.php?id=$idreserva&text=$text&type=$type");
        exit();
    }

    $sqlCancelar = "UPDATE reserva SET status = 'c' WHERE idreserva = {$idreserva}";
    if (mysqli_query($con, $sqlCancelar)) {

        //LOG 
        $date = date("Y-m-d");
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $descricaoLog = "Funcionário: <b>$funcionario</b>, cancelou a reserva <b>Nº $idreserva</b>, no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                          {$idFuncionario},
                                          'cancelar',
                                          '{$descricaoLog}',
                                          'reserva',    
                                          {$idreserva},
                                          null,
                                          '$date',
                                          '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        //FIM LOG

        $text = "Reserva cancelada com sucesso";
        $type = 0;
    } else {
        $text = "Erro ao cancelar: Entre com contato com o administrador do sistema";
        $type = 0;
    }
    mysqli_close($con);
    header("Location: ../visualizarReserva.php?id=$idreserva&text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}


    