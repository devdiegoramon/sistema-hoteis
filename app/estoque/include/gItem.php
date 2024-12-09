<?php

//VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $valor = $_POST['valor'];
    $quantidade = $_POST['quantidade'];
    $linhas = $_POST['linhas'];

    $i = 0;
    $cont = 1;
    for ($i; $i < $linhas; $i++) {
        $temp = $valor[$i];
        $valorItem = converteDecimal($temp);

        if (!empty(trim($nome[$i])) AND!empty(trim($categoria[$i])) AND!empty(trim($valor[$i]))) {
            $sqlConfere = "SELECT 1 FROM estoque WHERE item = '{$nome[$i]}'";
            $resultConfere = mysqli_query($con, $sqlConfere);
            if (mysqli_num_rows($resultConfere) == 0) {
                $date = date("Y-m-d"); //FORMATO AMERICANO 
                $data = date("d/m/Y");
                $hora = date("H:i:s");
                $sqlCadastro = "INSERT INTO estoque VALUES (null,
                                                    '{$nome[$i]}',
                                                    '{$categoria[$i]}',
                                                    '{$quantidade[$i]}',
                                                    '{$valorItem}',
                                                    's')";
                if (mysqli_query($con, $sqlCadastro)) {
                    $iditem = mysqli_insert_id($con); //ID DO ITEM RECÉM CADASTRADO
                    //GERA LOG
                    $idFuncionario = $_SESSION['idlogin'];
                    $funcionario = $_SESSION['login'];

                    $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou um item com nome: <b>$nome[$i]</b>, no dia <b>$data</b> às <b>$hora</b>";
                    $sqlLog = "INSERT INTO log VALUES (null,
                                               {$idFuncionario},
                                               'cadastro',
                                               '{$descricaoLog}',
                                               'estoque',
                                               {$iditem},
                                               null,    
                                               '$date',
                                               '$hora')";
                    if (mysqli_query($con, $sqlLog)) {
                        
                    }
                    //FIM LOG
                    //MOVIMENTAÇÃO DO ESTOQUE
                    $funcionario = $_SESSION['login'];
                    $sqlMov = "INSERT INTO movestoque VALUES (null,
                                                     {$iditem},
                                                     'entrada',
                                                     '{$quantidade[$i]}',
                                                     '{$funcionario}',
                                                     'compra',
                                                     '$date',
                                                     '$hora')";
                    if (mysqli_query($con, $sqlMov)) {
                        
                    }
                    //FIM MOVIMENTAÇÃO
                    $text = "Total de $cont itens cadastrados";
                    $type = 0;
                    $cont++;
                } else {
                    $text = "Cadastro não <b>realizado</b>: Erro ao cadastrar";
                    $type = 1;
                }
            } else {
                $text = "Cadastro <b>não</b> realizado: Item já cadastrado anteriormente";
                $type = 2;
            }
        }
    }
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}