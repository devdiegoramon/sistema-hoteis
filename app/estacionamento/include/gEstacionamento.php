<?php

//VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $numero = $_POST['numero'];

      //VERIFICA SE ALGUMA VAGA DE ESTACIONAMENTO JÁ ESTÁ CADASTRADA
    $sqlConfereNumero = "SELECT 1 FROM estacionamento WHERE numero = '{$numero}'";
    $resultConfereNumero = mysqli_query($con, $sqlConfereNumero);
    if (mysqli_num_rows($resultConfereNumero) > 0) {
        echo $text = "Cadastro não realizado: Número já cadastrado anteriormente";
        $type = 2;
    } else {
        //REALIZA CADASTRO NO BANCO DE DADOS
        $date = date("Y-m-d"); //FORMATO AMERICANO 
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $sqlCadastro = "INSERT INTO estacionamento VALUES (null,
                                                          null,
                                                          {$numero},
                                                          's')";
        if (mysqli_query($con, $sqlCadastro)) {
            $idestacionamento = mysqli_insert_id($con);
            
            //GERA LOG
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou uma vaga de estacionamento de número <b>$numero</b>, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'cadastro',
                                              '{$descricaoLog}',
                                              'estacionamento',
                                              {$idestacionamento},
                                              null,
                                              '$date',
                                              '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
            //FIM LOG
            $text = "Cadastro realizado com sucesso";
            $type = 0;
        } else {
            $text = "Cadastro não realizado: Erro ao cadastrar";
            $type = 1;
        }
    }
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}