<?php

include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";
include "include/dadosCard.php";

$dataSemana = date('Y-m-d', strtotime("-7 day", strtotime(date('Y-m-d'))));
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $iconeSite ?>
        <link href="<?= BASED ?>/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/fontawesome-5.15.4/css/all.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title> Home</title>
        <style>

            .badge-card{
                cursor:pointer;
            }

            table .badge-card{
                color:black;
            }

            .table-card {
                font-size: 90%;
            }
            .page-item.active .page-link {
                background-color: #5D6D7E !important;
                border-color: #5D6D7E !important;
            }
            .page-link {
                font-size: 80%;
                border: 1px solid #dee2e6;
                padding:0.125rem 0.65rem;
            }
            div.dataTables_wrapper div.dataTables_info{
                font-size: 80%;
            }
            .icon-card {
                color: rgba(0,0,0,.5);
                font-size: 2.7rem;
            }
            .myicon{
                height: 3rem;
            }

            .info-box {
                box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2);
                border-radius: .25rem;
                background-color: #fff;
                display: -ms-flexbox;
                display: flex;
                margin-bottom: 1rem;
                min-height: 80px;
                padding: .5rem;
                position: relative;
                width: 100%;
            }
            .info-box .info-box-icon {
                border-radius: 0.25rem;
                -ms-flex-align: center;
                align-items: center;
                display: -ms-flexbox;
                display: flex;
                font-size: 1.875rem;
                -ms-flex-pack: center;
                justify-content: center;
                text-align: center;
                width: 70px;
            }
            .info-box .info-box-number {
                display: block;
                margin-top: 0.25rem;
                font-weight: 700;
            }

            .text-card{
                font-weight: bold;
                font-family: sans-serif;
            }

            .card-dash {
                height: 6rem !important;
                box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.3);
            }

            .btn-badge{
                height:20px;
                cursor: pointer;
                border: none;
            }
            .btn-badge:hover{
                box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.5);
            }

        </style>
    </head>

    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12 mt-4">
                <div class="info-box">
                    <span class="info-box-icon bg-blue text-white"><i class="fa-solid fa-users"></i></span>
                    <div class="info-box-content p-2">
                        <span class="info-box-text">Hospedes</span>
                        <span class="info-box-number"><?= $quantidadeHospedes ?> atualmente </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mt-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success text-white"><i class="fa-solid fa-house-chimney-window"></i></span>
                    <div class="info-box-content p-2">
                        <span class="info-box-text">Acomodações </span>
                        <span class="info-box-number"><?= $quantAcDisponiveis ?> disponíveis</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mt-4">
                <div class="info-box">
                    <span class="info-box-icon bg-blue1 text-white"><i class="fa-solid fa-door-open"></i></span>
                    <div class="info-box-content p-2">
                        <span class="info-box-text">Check-in realizados</span>
                        <span class="info-box-number"><?= $checkinHj ?> hoje </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mt-4">
                <div class="info-box">
                    <span class="info-box-icon bg-danger text-white"><i class="fa-solid fa-door-closed"></i></span>
                    <div class="info-box-content p-2">
                        <span class="info-box-text">Check-out realizados</span>
                        <span class="info-box-number"><?= $checkoutHj ?> hoje </span>
                    </div>
                </div>
            </div>
        </div> 

        <!--CONTEUDO-->
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="bg-blue1 text-white card-dash mt-3">
                    <div class=" row line-card p-3">
                        <div class="text-card col-8"> 
                            <span> Últimas <br> reservas </span> 
                        </div>
                        <div class="col-4"> 
<!--                                <i class="icon-card fa-solid fa-house-chimney-medical"></i>-->
                            <img class="myicon" src="img/calendar.png" alt="alt"/>
                        </div>
                    </div>
                    <div onclick="ultimasReservas()" class="footer-card text-center"> 
                        <span> Visualizar </span> <i class="fa-solid fa-angles-right"></i>
                    </div>
                </div>
                <div class="bg-yellow text-black-50 card-dash mt-3">
                    <div class="row line-card p-3">
                        <div class="text-card col-8"> 
                            <span> Check-in <br> pendentes </span> <br>
                        </div>
                        <div class="col-4"> 
                            <img class="myicon" src="img/checkin.png" alt="alt"/>
                        </div>
                    </div>
                    <div onclick="checkinPendentes()" class="footer-card text-center"> 
                        <span> Visualizar </span> <i class="fa-solid fa-angles-right"></i>
                    </div>
                </div>
                <div class="bg-danger text-white card-dash mt-3">
                    <div class="row line-card p-3">
                        <div class="text-card col-8"> 
                            <span> Check-out <br>  pendentes </span> <br>
                        </div>
                        <div class="col-4"> 
                            <img class="myicon" src="img/checkout.png" alt="alt"/>
                        </div>
                    </div>
                    <div onclick="checkoutPendentes()" class="footer-card text-center"> 
                        <span> Visualizar </span> <i class="fa-solid fa-angles-right"></i>
                    </div>
                </div>
                <div class="bg-blue1 text-white card-dash mt-3">
                    <div class="row line-card p-3">
                        <div class="text-card col-8"> 
                            <span> Acomodações <br>  disponíveis </span> <br>
                        </div>
                        <div class="col-4"> 
                            <i class="icon-card fa-solid fa-house-chimney"></i>
                        </div>
                    </div>
                    <div onclick="acomodacoesDisponiveis()" class="footer-card text-center"> 
                        <span> Visualizar </span> <i class="fa-solid fa-angles-right"></i>
                    </div>
                </div>

                <div class="bg-blue text-white card-dash mt-3 mb-3">
                    <div class="row line-card p-3">
                        <div class="text-card col-8"> 
                            <span> Acomodações <br>  Ocupadas </span> <br>
                        </div>
                        <div class="col-4"> 
                            <i class="icon-card fa-solid fa-house-chimney-user"></i>
                        </div>
                    </div>
                    <div onclick="acomodacoesOcupadas()" class="footer-card text-center"> 
                        <span> Visualizar </span> <i class="fa-solid fa-angles-right"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-7 col-sm-12 mt-3"> 
                <div class="card mb-3 shadow">
                    <div class="card-header d-flex justify-content-between">
                        <div id="titulo-card"> 
                            <span> <i class="fa-solid fa-calendar-days mr-3"></i> Reservas nos últimos 7 dias </span>
                        </div>
                        <div class="">
                            <form id="form-baixar" method="GET" action="include/gRelatorioUltimasReservas.php" target="_blank"> 
                                <button name="gerar" class="btn-badge badge bg-blue1 badge-card "> Baixar <i class="fa-solid fa-download"></i> </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body" id="conteudo-card"> 
                        <div class="table-responsive"> 
                            <table class="table-card table table-secondary table-hover table-striped" id="datatable"> 
                                <thead style="border-radius:3em;"> 
                                    <tr> 
                                        <th> ID </th>
                                        <th> Acomodação </th>
                                        <th> Cliente </th>
                                        <th> Entrada prevista </th>
                                        <th> Saida prevista </th>
                                        <th class="text-center"> Situação </th>
                                        <th class="text-center"> Ação </th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <?php
                                    $sqlUltimaSemana = "SELECT r.idreserva,
                                                                   r.idacomodacao,
                                                                   r.entradaprevista,
                                                                   r.saidaprevista,
                                                                   r.status,
                                                                   a.nome,
                                                                   c.nome
                                                            FROM reserva r       
                                                            INNER JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                                                            INNER JOIN cliente c ON (r.idcliente = c.idcliente)
                                                            WHERE (r.entradaprevista >= date('{$dataSemana}') and saidaprevista <= date('{$dataAtual}')
                                                            OR saidaprevista >= date('{$dataSemana}') and entradaprevista <= date('{$dataAtual}'))";
                                    $resultUltimaSemana = mysqli_query($con, $sqlUltimaSemana);
                                    while ($rowUltimaSemana = mysqli_fetch_array($resultUltimaSemana)) {
                                        ?>
                                        <tr> 
                                            <td> <?= $rowUltimaSemana[0] ?> </td>
                                            <td> <?= $rowUltimaSemana[5] ?> </td>
                                            <td> <?= $rowUltimaSemana[6] ?> </td>
                                            <td> <?= dataBrasil($rowUltimaSemana[2]) ?> </td>
                                            <td> <?= dataBrasil($rowUltimaSemana[3]) ?> </td>
                                            <td class="text-center"> <?= statusReserva($rowUltimaSemana[4], 1) ?> </td> 
                                            <td class="text-center" title="visualizar"> <a href="../reserva/visualizarReserva.php?id=<?= $rowUltimaSemana[0] ?>" class="badge-card badge bg-blue1"> <i class="fa-solid fa-eye"></i> </a> </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
        <!--bootstrap js-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script>
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <!--Mask input-->
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <!--datepicker-->
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--Chart.js-->
        <script src="<?= BASED ?>/assets/vendor/chart/chart.min.js"></script>
        <!--js-->
        <script src="js/dashboard.js"></script>
        <script src="<?= BASED ?>/assets/js/main.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
    </body>
</html>

