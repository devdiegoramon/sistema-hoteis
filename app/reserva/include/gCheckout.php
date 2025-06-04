<?php

if (isset($_POST['idreserva'])) {
    include "../../config/connMysql.php";
    include "../../include/func.php";
    include "../../include/funcGeraComprovante.php";
    include "../../config/config_gCheckout.php";

    $idreserva = $_POST['idreserva'];
    if (!isset($_SESSION['idreservacomprovante']) OR $_SESSION['idreservacomprovante'] != $idreserva) {
        $idconsumo = $_POST['idconsumo'];
        $valortotal = converteDecimal(substr(str_replace('R$', '', $_POST['totalgeral']), 2)); //RETIRA R$ E CONVERTE PARA DECIMAL
        $temp = $_POST['valor-estadia'];
        $temp2 = explode("R$ ", $temp);
        $valorestadia = converteDecimal($temp2[1]);
        $dataCheck = date("Y-m-d");
        $horaCheck = date('H:i:s');

        $valordesconto = converteDecimal($_POST['valordesconto']); //RETIRA R$ E CONVERTE PARA DECIMAL
        $formapagamento = $_POST['formapagamento'];

        $comprovante = $idreserva . "_" . substr(md5(uniqid(rand(), true)), 0, 20) . ".pdf"; //Nome gerado para o comprovante

        $valortotaladicional = 0;
        if (isset($_POST['valor-adicional'])) {
            $valoradicional = $_POST['valor-adicional'];
            $descricaoadicional = $_POST['descricao-adicional'];
            $cont = 0;

            //CADASTRA O CONSUMO ADICIONAL E SOMA VALOR TOTAL
            foreach ($valoradicional as $val) {
                $sqlConsumoAdicional = "INSERT INTO adicionalconsumo VALUES (null,
                                                                       {$idconsumo},
                                                                       '{$descricaoadicional[$cont]}',
                                                                       " . converteDecimal($val) . ")";

                $resultConsumoAdicional = mysqli_query($con, $sqlConsumoAdicional);
                $valortotaladicional += converteDecimal($val);
                $cont++;
            }
        }

        //FECHA A TABELA DE CONSUMO
        $sqlConsumo = "UPDATE consumo SET status = 'concluido',
                                      valorestadia = $valorestadia,
                                      valoradicional = " . converteDecimal($valortotaladicional) . ",
                                      datafechamento = '{$dataCheck}',
                                      horafechamento = '{$horaCheck}',
                                      valorfinal = $valortotal,
                                      totaldesconto = $valordesconto,
                                      formapagamento = '$formapagamento',
                                      comprovantepagamento = '$comprovante'
                   WHERE idconsumo = {$idconsumo}";

        if (mysqli_query($con, $sqlConsumo)) {

            //FINALIZA A RESERVA
            $sqlCheckout = "UPDATE reserva SET datacheckout = '{$dataCheck}',
                                           horacheckout = '{$horaCheck}',
                                           status = 'f'    
                        WHERE idreserva = {$idreserva}";

            if (mysqli_query($con, $sqlCheckout)) {
                $dadosCheckout = [];
                $dadosCheckout = ([
                    'idreserva' => $idreserva,
                    'acao' => 'check-out',
                    'nomefuncionario' => $_SESSION['nome'],
                    'datacheckout' => $dataCheck,
                    'horacheckout' => $horaCheck
                ]);

                //LOG
                $date = date("Y-m-d");
                $data = date("d/m/Y");
                $hora = date("H:i:s");
                $idFuncionario = $_SESSION['idlogin'];
                $funcionario = $_SESSION['login'];
                $descricaoLog = "Funcionário: <b>$funcionario</b>, realizou o Check-out na reserva <b>Nº $idreserva</b>, no dia <b>$data</b> às <b>$hora</b>";
                $sqlLog = "INSERT INTO log VALUES (null,
                                          {$idFuncionario},
                                          'checkout',
                                          '{$descricaoLog}',
                                          'reserva',    
                                          {$idreserva},
                                          '" . json_encode($dadosCheckout) . "',
                                          '$date',
                                          '$hora')";
                if (mysqli_query($con, $sqlLog)) {
                    
                }
                //FIM LOG
            }

            $text = "Check-out realizado com sucesso";
            $type = 0;
            gerarComprovante($comprovante, $idreserva, $con);
        } else {
            $text = "Erro ao realizar check-out";
            $type = 2;
        }

        header("Location: ../visualizarReserva.php?id=$idreserva&text=$text&type=$type");
        mysqli_close($con);
    } else {
        unset($_SESSION['idreservacomprovante']);
        $text = "Check-out realizado com sucesso";
        $type = 0;
        header("Location: ../visualizarReserva.php?id=$idreserva&text=$text&type=$type");
        mysqli_close($con);
    }
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}



    