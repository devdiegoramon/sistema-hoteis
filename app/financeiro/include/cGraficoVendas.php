<?php

if (isset($_POST['start'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    //RANGED 
    $start = $_POST['start'][0];
    $end = $_POST['end'][0];

    //ARRAY COM INFORMAÇÕES PARA O AJAX
    $dados = array(
        'valor' => array(), //Valor das vendas
        'label' => array(), //Datas das vendas
        'itens' => array(), //Itens vendidos (ex: heineken, coca-cola)
        'quantidade' => array(), //Quantidade de Itens vendidos (ex: 10 heineken)
        'quantidadetotal' => array(), //Quantidade final
        'total' => array(), //Valor total de vendas
        'titulo' => array(
            "Vendas entre " . dataBrasil($start) . " e " . dataBrasil($end)
        ),
        'tituloitens' => array(
            "Itens vendidos entre " . dataBrasil($start) . " e " . dataBrasil($end)
        )
    );

    $valorTotalProdutos = 0; //Variaveis usadas posteriormente para calculos
    $quantTotalProdutos = 0; //Variaveis usadas posteriormente para calculos

    $sqlVendas = "SELECT datag, 
                         nome,
                         quantidade
                  FROM itensconsumidos
                  WHERE datag BETWEEN date('{$start}') AND date('{$end}')";
    $resultVendas = mysqli_query($con, $sqlVendas);

    //SELECIONANDO AS VENDAS ENTRE O RANGED DE DATAS
    while ($rowVendas = mysqli_fetch_array($resultVendas)) {
        $dataLabel = explode('/', dataBrasil($rowVendas[0]));
        $dataLabel = "$dataLabel[0]/$dataLabel[1]"; //data para visualização
        //Valor das vendas por dia
        if (!in_array($dataLabel, $dados['label'])) {
            $valorTotal = 0;
            $sqlDadosVendas = "SELECT valortotal 
                               FROM itensconsumidos 
                               WHERE datag = '{$rowVendas[0]}'";
            $resultDadosVendas = mysqli_query($con, $sqlDadosVendas);

            //Looping para guardar a data (00/00) e Lucro do dia
            while ($rowDadosVendas = mysqli_fetch_array($resultDadosVendas)) {
                $valorTotal = $valorTotal + $rowDadosVendas[0];
            }

            $dados['label'][] = $dataLabel;
            $dados['valor'][] = $valorTotal;
            $valorTotalProdutos = $valorTotalProdutos + $valorTotal;
        }

        //Itens e quantidade vendida
        if (!in_array($rowVendas[1], $dados['itens'])) {
            $quantidade = 0;
            $sqlDadosItens =  "SELECT quantidade 
                               FROM itensconsumidos 
                               WHERE nome = '{$rowVendas[1]}'";
            $resultDadosItens = mysqli_query($con, $sqlDadosItens);

            //Looping para guardar nome do item e quantidade vendida
            while ($rowDadosItens = mysqli_fetch_array($resultDadosItens)) {
                $quantidade = $quantidade + $rowDadosItens[0];
            }

            $dados['itens'][] = $rowVendas[1];
            $dados['quantidade'][] = $quantidade;
            $quantTotalProdutos = $quantTotalProdutos + $quantidade;
        }
    }

    $dados['total'][] = "R$ " . converteReal($valorTotalProdutos);
    $dados['quantidadetotal'][] = $quantTotalProdutos . " itens vendidos";
    echo json_encode($dados);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text = $text&type = 1");
}    