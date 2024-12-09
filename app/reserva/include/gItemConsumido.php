<?php

//VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['acrescentar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $idreserva = $_GET['idreserva'];
    $idconsumo = $_GET['idconsumo'];
    $idfrigobar = $_POST['frig'];
    $iditemfrigobar = $_POST['item-frig'];
    $quantidade = $_POST['quantidade-frig'];

    //CONVERTENDO VALOR RECEBIDO EM DECIMAL
    $valor = $_POST['valor-itemFrig']; //R$ 000,00
    $valor = converteDecimal(substr(str_replace('R$', '', $valor), 2)); // 000.00;
    //DADOS DO ITEM DENTRO DO FRIGOBAR
    $sqlDadosItem = "SELECT i.iditemfrigobar,
                            i.iditem,
                            i.quantidade,
                            e.item
                    FROM itemfrigobar i
                    INNER JOIN estoque e ON (i.iditem = e.iditem)
                    WHERE i.iditemfrigobar = {$iditemfrigobar}";
    $resultDadosItem = mysqli_query($con, $sqlDadosItem);
    $rowDadosItem = mysqli_fetch_array($resultDadosItem);

    $novaQuantidade = $rowDadosItem[2] - $quantidade; //QUANTIDADE FUTURA DENTRO DO FRIGOBAR
    
    //ATUALIZANDO QUANTIDADE DE ITENS DENTRO DO FRIGOBAR
    $sqlItemFrigobar = "UPDATE itemfrigobar 
                        SET quantidade = {$novaQuantidade}
                        WHERE iditemfrigobar = {$iditemfrigobar}";
    if (mysqli_query($con, $sqlItemFrigobar)) {
        $date = date('Y-m-d');
        $time = date('H:i:s');

        //REGISTRANDO MOVIMENTAÇÃO DO ESTOQUE
        $funcionario = $_SESSION['login'];
        $sqlMov = "INSERT INTO movestoque VALUES (null,
                                                {$rowDadosItem[1]},
                                                'saida',
                                                {$quantidade},
                                                '{$funcionario}',
                                                'venda',
                                                '{$date}',
                                                '{$time}')";

        if (mysqli_query($con, $sqlMov)) {
            //FIM MOVIMENTAÇÃO
        }

        //REGISTRANDO DADOS DO CONSUMO
        $sqlConsumido = "INSERT INTO itensconsumidos VALUES (null,
                                                            {$idconsumo},
                                                            {$rowDadosItem[1]},
                                                            '{$rowDadosItem[3]}',
                                                            {$quantidade},
                                                            {$valor},
                                                            '{$date}',
                                                            '{$time}')";
        if (mysqli_query($con, $sqlConsumido)) {
            //ATUALIZA VALOR TOTAL DOS ITENS CONSUMIDOS
            $sqlDadosConsumo = "SELECT valoritens, valorestadia FROM consumo WHERE idconsumo = {$idconsumo}";
            $resultDadosConsumo = mysqli_query($con, $sqlDadosConsumo);
            $rowConsumo = mysqli_fetch_array($resultDadosConsumo);

            if ($rowConsumo[0] == '' OR $rowConsumo[0] == null) {
                $novoValorItens = $valor;
            } else {
                $novoValorItens = converteDecimal($rowConsumo[0] + $valor);
            }

            $novoValorFinal = converteDecimal($novoValorItens + $rowConsumo[1]);
            $sqlNovoValorConsumo = "UPDATE consumo SET 
                                    valoritens = {$novoValorItens},
                                    valorfinal = {$novoValorFinal}    
                                    WHERE idconsumo = {$idconsumo}";

            if (mysqli_query($con, $sqlNovoValorConsumo)) {
                $text = "Consumo realizado com sucesso";
                $type = 0;
            } else {
                $text = "Erro ao cadastrar o valor total";
                $type = 1;
            }
        } else {
            $text = "Erro ao cadastrar o valor";
            $type = 1;
        }
    } else {
        $text = "Erro ao cadastrar o valor";
        $type = 1;
    }

    mysqli_close($con);
    header("Location: ../visualizarReserva.php?id=$idreserva&text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}