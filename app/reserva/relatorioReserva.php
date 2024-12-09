<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";

$start = '0';
$end = '0';
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
        <link href="<?= BASED ?>/assets/vendor/fullcalendar/main.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Relatóri</title>
        <style>

            .page-item.active .page-link {
                background-color: #5D6D7E !important;
                border-color: #34495E !important;
            }
            .page-link {
                font-size: 80%;
                color: black !important;
                border: 1px solid #dee2e6;
                padding:0.125rem 0.65rem;
            }
            div.dataTables_wrapper div.dataTables_info{
                font-size: 80%;
            }

            .modal-open {
                padding-right: 16px !important;
            }

            body {
                overflow-y: scroll !important;
            }
            .modal-open {
                padding-right: 16px !important;
            }

            body {
                overflow-y: scroll !important;
            }

        </style>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <a class="btn btn-sm btn-dark-blue mt-4" onclick="voltar()"> <i class="fa-regular fa-circle-left"></i> Voltar </a>
        <div class="row d-flex mt-3">
            <div class="col-lg-7 col-md-12 col-sm-12 grafico ">
                <div class="card mb-3 shadow">
                    <div class="card-header"> 
                        <div class="row d-flex justify-content-between pt-1">
                            <div class="col-md-5">
                                <h5>Histórico de reservas</h5>
                            </div>
                            <div class="col-md-7">
                                <div class="float-end"> 
                                    <div class="input-daterange input-group" data-provide="datepicker">
                                        <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" name="start" id="start">
                                        <span class="input-group-addon range-to" style="margin: 0 5px;">até</span>
                                        <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" name="end" id="end" data-date-format="dd/mm/yyyy">
                                        <a title="pesquisar" class="btn btn-sm bg-blue1 text-white" onclick="pesquisarReservas()"> <i class="fa-solid fa-magnifying-glass"></i> Buscar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body"> 
                        <div class="table-responsive">
                            <table style="font-size:85%;" class="table table-hover table-secondary" id="datatable"> 
                                <thead>
                                    <tr>
                                        <th title="ID da reserva"> ID </th>
                                        <th> Acomodação </th>
                                        <th> Entrada Prevista </th>
                                        <th> Saida Prevista </th>
                                        <th class="text-center"> Situação </th>
                                        <th class="text-center"> Visualizar </th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-dados"> 
                                    <?php
                                    $sqlReservas = "SELECT r.idreserva,
                                                               a.nome,
                                                               r.quantidadehospedes,
                                                               r.entradaprevista,
                                                               r.saidaprevista,
                                                               r.status
                                                        FROM reserva r 
                                                        INNER JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                                                        ORDER BY idreserva DESC
                                                        ";
                                    $resultReservas = mysqli_query($con, $sqlReservas);
                                    while ($rowReservas = mysqli_fetch_array($resultReservas)) {
                                        ?>
                                        <tr> 
                                            <td> <?= $rowReservas[0] ?> </td>
                                            <td> <?= $rowReservas[1] ?> </td>
                                            <td> <?= dataBrasil($rowReservas[3]) ?> </td>
                                            <td> <?= dataBrasil($rowReservas[4]) ?> </td>
                                            <td class="text-center"> <?= statusReserva($rowReservas[5], 1) ?> </td>
                                            <td class="text-center" title="visualizar"> <a href="../reserva/visualizarReserva.php?id=<?= $rowReservas[0] ?>" class="badge-card badge bg-blue1 text-dark"> <i class="fa-solid fa-eye"></i> </a> </td>
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
            <div class="col-lg-5 col-md-12 col-sm-12 grafico">
                <div class="card mb-3 shadow">
                    <div class="card-header">
                        <h5 class="pt-1">  Acomodações mais alugadas </h5>
                    </div>
                    <div class="card-body"> 
                        <canvas id="chart-maisAlugadas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!--jquery-->
        <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
        <!--bootstrap js-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script>
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--Mask input-->
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <!--Chart.js-->
        <script src="<?= BASED ?>/assets/vendor/chart/chart.min.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <!--date picker-->
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/date-picker/datepicker.pt-BR.min.js"></script>
        <!--js-->
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="js/relatorioReserva.js"></script>
    </body>

</html>