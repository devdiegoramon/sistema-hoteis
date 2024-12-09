<?php

if (isset($_POST['btn-login'])) {
    include "../app/config/connMysql.php";
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    
    session_start();

// Padrão brasileiro
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

    $sqlVerifica = "SELECT idlogin,
                           login, 
                           nome,
                           nivel,
                           senha
                    FROM funcionario 
                    WHERE login = '{$login}' 
                    AND ativo = 's'";
    $resultVerifica = mysqli_query($con, $sqlVerifica);
    if (mysqli_num_rows($resultVerifica) > 0) {
        $row = mysqli_fetch_array($resultVerifica);
        if (password_verify($senha, $row[4])) { //VERIFICA O HASH DE SENHA
            $data = date("Y-m-d");
            $_SESSION['idlogin'] = $row[0];
            $_SESSION['login'] = $row[1];
            $_SESSION['nome'] = $row[2];
            $_SESSION['nivel'] = $row[3];
            $_SESSION["tempo"] = time();
            $dateTime = date("Y-m-d H:i:s");
            $sqlLogAcesso = "INSERT INTO logacesso VALUES (null,
                                                           {$row[0]},
                                                           '{$dateTime}',
                                                           '{$_SERVER['REMOTE_ADDR']}')";

            if (mysqli_query($con, $sqlLogAcesso)) {
                
            }
            header("Location: ../app/home");
            return;
        } else {
            $text = "Senha inválida";
            $type = 1;
        }
    } else {
        $text = "Usuário não cadastrado";
        $type = 1;
    }
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    $type = 1;
    header("Location: ../index.php?text=$text&type=$type");
}
