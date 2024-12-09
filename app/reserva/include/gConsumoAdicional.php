<?php

include "../../config/config.php";
if (isset($_POST['adicionar'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";

    $idreserva = $_GET['idreserva'];
    $idconsumo = $_GET['idconsumo'];
    $descricao = $_POST['descricao'];
    $valor = converteDecimal($_POST['valor']);

    $sqlConsumoAdicional = "INSERT INTO adicionalconsumo VALUES (null,
                                                                {$idconsumo},
                                                                '{$descricao}',
                                                                '{$valor}')";

    if (mysqli_query($con, $sqlConsumoAdicional)) {
        //ATUALIZA VALOR TOTAL DOS ITENS CONSUMIDOS
        $sqlDadosConsumo = "SELECT valoritens FROM consumo WHERE idconsumo = {$idconsumo}";
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

        //LOG
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $idFuncionario = $_SESSION['idlogin'];
        $funcionario = $_SESSION['login'];
        $descricaoLog = "Funcionário: <b>$funcionario</b>, cadastrou um consumo adicinal à reserva de <b>Nº $idreserva</b> , no dia <b>$data</b> às <b>$hora</b>";
        $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'consumo adicional',
                                              '{$descricaoLog}',
                                              'adicionalconsumo',
                                              {$idreserva},
                                              null,
                                              '$date',
                                              '$hora')";
        if (mysqli_query($con, $sqlLog)) {
            
        }
        //FIM LOG
    } else {
        $text = "Erro ao adicionar consumo";
        $type = 1;
    }

    mysqli_close($con);
    header("Location: ../visualizarReserva.php?id=$idreserva&text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}


    
