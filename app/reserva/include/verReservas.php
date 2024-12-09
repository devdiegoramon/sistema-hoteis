<?php

if (isset($_POST['start'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $start = dataAmerica($_POST['start']);
    $end = dataAmerica($_POST['end']);
    $idReserva = isset($_POST['idReserva']) ? $_POST['idReserva'] : 0;

    $inicio = new DateTime($start);
    $fim = new DateTime($end);
    $tempDias = $inicio->diff($fim);
    $dias = $tempDias->days; //CALCULANDO DIAS DE ESTADIA

    if ($dias == 0) {
        $dias = 1;
    }

    if ($start <= $end) {
        $linha = 0;
        //VERIFICA SE A ACOMODAÇÃO ESTÁ DISPONIVEL
        $sqlAcomodacao = "SELECT a.idacomodacao,
                                     a.nome,
                                    a.numero
                             FROM acomodacao a
                             WHERE ativo = 's'
                             AND not exists(
                                          SELECT 1 FROM reserva r
                                          WHERE (entradaprevista >= date('{$start}') and saidaprevista <= date('{$end}')
                                          OR saidaprevista >= date('{$start}') and entradaprevista <= date('{$end}'))
                                          AND r.idacomodacao = a.idacomodacao
                                          AND (r.status != 'c')
                                          AND (r.status != 'f')
                                          AND idreserva != $idReserva)";
        $resultAcomodacao = mysqli_query($con, $sqlAcomodacao);
        if (mysqli_num_rows($resultAcomodacao) > 0) {
        $option = '<option selected disabled> selecione uma opção </option>';
            while ($rowAcomodacao = mysqli_fetch_array($resultAcomodacao)) {

                $linha++;
                $option .= "<option value='$rowAcomodacao[0]'> $rowAcomodacao[1] - Nº$rowAcomodacao[2] </option>";

                //CRIANDO UM JSON PARA PODER FILTRAR AS INFORMAÇÔES DA REQUISIÇÂO
                $resposta = "$linha acomodações disponíveis";
                $dados = array('resposta' => $resposta, 'option' => $option, 'acomodacoes' => "$linha", 'tipo' => '0');
            }
        } else {
            $resposta = "Nenhuma acomodação disponível ou cadastrada";
            $dados = array('resposta' => $resposta, 'tipo' => '1');
        }
    } else {
        $resposta = "Range inválido";
        $dados = array('resposta' => $resposta, 'tipo' => '1');
    }
    $dados['diarias'] = $dias;
    echo json_encode($dados);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}