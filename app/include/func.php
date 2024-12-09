<?php

function dataAmerica($data) {
    $data = explode('/', $data);
    $data = array_reverse($data);
    $data = implode('-', $data);
    return $data;
}

function dataBrasil($data) {
    $data = explode('-', $data);
    $data = array_reverse($data);
    $data = implode('/', $data);
    return $data;
}

//CONVERTER NÙMEROS PARA VISUALIZAÇÂO DO USUARIO 
function converteReal($valor) { //10000.00
    $valor = number_format($valor, 2, '.', '');
    $valor = explode('.', $valor);
    $decimal = $valor[1];
    $valor = $valor[0];
    $valor = array_map("strrev", array_reverse(str_split(strrev($valor), 3)));
    $valor = implode('.', $valor);
    $valor .= ",$decimal";
    return $valor; //10.000,00
}

function converteDecimal($valor) {
    $valor = str_replace(".", "", $valor); //FORMATA O VALOR PARA CADASTRAR NO BANCO
    $valor = str_replace(",", ".", $valor);
    return $valor; //10.000,00
}

//FUNÇÃO PARA CONVERTER OS MESES
function nomeMes($mes) {
    switch ($mes) {
        case 1:
            $mes = "Janeiro";
            break;
        case 2:
            $mes = "Fevereiro";
            break;
        case 3:
            $mes = "Março";
            break;
        case 4:
            $mes = "Abril";
            break;
        case 5:
            $mes = "Maio";
            break;
        case 6:
            $mes = "Junho";
            break;
        case 7:
            $mes = "Julho";
            break;
        case 8:
            $mes = "Agosto";
            break;
        case 9:
            $mes = "Setembro";
            break;
        case 10:
            $mes = "Outubro";
            break;
        case 11:
            $mes = "Novembro";
            break;
        case 12:
            $mes = "Dezembro";
            break;
    }
    return $mes;
}

function statusReserva($status, $param) {
    if ($param == 0) {
        switch ($status) {
            case 'i':
                $status = 'Iniciado';
                break;
            case 'p':
                $status = 'Pendente';
                break;
            case 'c':
                $status = 'Cancelado';
                break;
            case 'f':
                $status = 'Finalizado';
                break;
        }
    } elseif ($param == 1) {
        switch ($status) {
            case 'i':
                $status = '<span class="badge bg-primary"> Iniciado </span>';
                break;
            case 'p':
                $status = '<span class="badge text-black bg-warning"> pendente </span>';
                break;
            case 'c':
                $status = '<span class="badge bg-danger"> cancelado </span>';
                break;
            case 'f':
                $status = '<span class="badge bg-success"> Concluido </span>';
                break;
        }
    }
    return $status;
}

function alerta($texto, $alerta) {
    switch ($alerta) {
        case 0:
            $alerta = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check"></i> ' . $texto . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            break;
        case 1:
            $alerta = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-triangle-exclamation"></i> ' . $texto . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            break;
        case 2:
            $alerta = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                       <i class="fa-solid fa-triangle-exclamation"></i> ' . $texto . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            break;
    }
    return $alerta;
}
