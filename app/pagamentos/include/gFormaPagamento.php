<?php

//VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['nome'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    $nome = $_POST['nome'];

    if (empty(trim($nome))) {
        echo '<span class="border border-warning text-black badge mb-3"><i class="fa-solid fa-circle-exclamation"></i> Por favor, digite um nome.</span>';
    } else {
        $sqlConfere = "SELECT 1 FROM formapagamento WHERE nome = '{$nome}'";
        $resultConfere = mysqli_query($con, $sqlConfere);
        if (mysqli_num_rows($resultConfere) == 0) {
            $sqlCadastro = "INSERT INTO formapagamento VALUES (null,
                                                               '{$nome}',
                                                               's')";
            if (mysqli_query($con, $sqlCadastro)) {
                echo '<span class="border border-success text-success badge mb-3"><i class="fa-solid fa-circle-check"></i> Forma de pagamento cadastrada com sucesso.</span>';
                //GERA LOG
                $idtipo = mysqli_insert_id($con);
                $idFuncionario = $_SESSION['idlogin'];
                $funcionario = $_SESSION['login'];
                $date = date("Y-m-d"); //FORMATO AMERICANO 
                $data = date("d/m/Y");
                $hora = date("H:i:s");
                $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou uma forma de pagamento com nome: <b>$nome</b>, no dia <b>$data</b> às <b>$hora</b>";
                $sqlLog = "INSERT INTO log VALUES (null,
                                                     {$idFuncionario},
                                                     'cadastro',
                                                     '{$descricaoLog}',
                                                     'formapagamento',
                                                     {$idtipo},
                                                     null,    
                                                     '$date',
                                                     '$hora')";
                if (mysqli_query($con, $sqlLog)) {
                    
                }
                //FIM LOG
            } else {
                echo '<span class="border border-danger text-danger badge mb-3"><i class="fa-solid fa-circle-xmark"></i> Erro ao cadastrar forma de pagamento.</span>';
            }
        } else {
            echo '<span class="border border-warning text-black badge mb-3"><i class="fa-solid fa-circle-exclamation"></i> Forma de pagamento já cadastrada anteriormente</span>';
        }
    }
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}