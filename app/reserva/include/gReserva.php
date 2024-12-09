<?php

//VERIFICAÇÃO PARA ACESSAR
if (isset($_POST['cadastrar'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $idcliente = $_POST['cliente'];
    $start = dataAmerica($_POST['start']); //CONVERTE DATA EM FORMATO AMERICANO
    $end = dataAmerica($_POST['end']);
    $idacomodacao = $_POST['acomodacao'];
    $hospedes = $_POST['hospedes'];
    $obs = $_POST['obs'];

    $tempValorDiaria = converteDecimal($_POST['valordiaria']); //CONVERTE EM DECIMAL PARA CADASTRAR NO BANCO
    $tempValorTotal = converteDecimal($_POST['total']); //CONVERTE EM DECIMAL PARA CADASTRAR NO BANCO
    $valorDiaria = $tempValorDiaria > 00.00 ? $tempValorDiaria : 00.00;
    $valor = $tempValorTotal > 00.00 ? $tempValorTotal : 00.00;

    //CONFERE SE JÁ EXISTE RESERVA NESSE RANGE DE DATAS
    $sqlConfere = "SELECT 1 FROM reserva
                    WHERE (entradaprevista >= date('{$start}') and saidaprevista <= date('{$end}')
                    OR saidaprevista >= date('{$start}') and entradaprevista <= date('{$end}'))
                    AND idacomodacao = {$idacomodacao}
                    AND (status != 'c')
                    AND (status != 'f')";
    $resultConfere = mysqli_query($con, $sqlConfere);
    if (mysqli_num_rows($resultConfere) == 0) {

        //CONFERE SE O CLIENTE JÁ ESTÁ HOSPEDADO EM OUTRO QUARTO
        $date = date('Y-m-d');
        $hora = date('H:i:s');

        $sqlReserva = "INSERT INTO reserva VALUES (null,
                                                  {$idacomodacao},
                                                  {$idcliente},
                                                  {$hospedes},
                                                  '{$start}',
                                                  '{$end}',
                                                  null,
                                                  null,
                                                  null,
                                                  null,
                                                  '{$date}',
                                                  '{$hora}',
                                                  '{$obs}',
                                                  'p',
                                                   $valorDiaria)";
        if (mysqli_query($con, $sqlReserva)) {
            //ADICIONA O VALOR DA ESTADIA (VALOR PODERÁ SER ALTERADO CONFORME DIARIAS)
            $idreserva = mysqli_insert_id($con);
            $sqlConsumo = "INSERT INTO consumo VALUES (null,
                                                      {$idreserva},
                                                      {$valor},
                                                       null,
                                                       null,
                                                      'pendente',
                                                       null,
                                                       null,
                                                       {$valor},
                                                       null,
                                                       null,
                                                       null,
                                                       null)";
            $resultConsumo = mysqli_query($con, $sqlConsumo);

            //LOG
            $data = date("d/m/Y");
            $hora = date("H:i:s");
            $idFuncionario = $_SESSION['idlogin'];
            $funcionario = $_SESSION['login'];
            $descricaoLog = "Funcionário: <b>$funcionario</b>, realizou a reserva de <b>Nº $idreserva</b> para o dia " . dataBrasil($start) . " até " . dataBrasil($end) . " na acomodação Nº$idacomodacao, no dia <b>$data</b> às <b>$hora</b>";
            $sqlLog = "INSERT INTO log VALUES (null,
                                              {$idFuncionario},
                                              'cadastro',
                                              '{$descricaoLog}',
                                              'reserva',
                                              {$idreserva},
                                              null,
                                              '$date',
                                              '$hora')";
            if (mysqli_query($con, $sqlLog)) {
                
            }
            //FIM LOG
            $text = "Reserva realizada com sucesso";
            $type = 0;
        } else {
            $text = "Erro ao realizar a reserva";
            $type = 1;
        }
    } else {
        $text = "Já possuí uma acomodação cadastrada entre " . dataBrasil($start) . " e " . dataBrasil($end);
        $type = 2;
    }
    mysqli_close($con);
    header("Location: ../index.php?text=$text&type=$type");
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}