<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";
include "include/valores.php";

$primeirodia = date("Y-m-01"); //´Primeiro dia do Mês
$ultimodia = date("Y-m-t"); //Ultimo dia do Mês

$valorFinalMes = 0;
$sqlValorMes = "SELECT idconsumo,
                       idreserva,
                       valorestadia,
                       valoritens,
                       valorfinal
                FROM consumo 
                WHERE datafechamento BETWEEN date('{$primeirodia}') AND date('{$ultimodia}')";
$resultValorMes = mysqli_query($con, $sqlValorMes);

while ($rowMes = mysqli_fetch_array($resultValorMes)) {
    $valorFinalMes = $valorFinalMes + $rowMes[4];
}
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
        <title> Financeiro</title>
        <style>
            .grafico{
                border-radius: 0.3rem;
            }

            #valor-grafico{
                font-weight: bolder;
                font-family: sans-serif;
                color:#212F3D;
            }
            .table-vendas {
                font-size: 80%;
            }
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
            .value-card{
                font-size: 1rem;
                font-weight: bolder;
            }

            .desc-card{
                font-size: 75%;
                font-weight: bolder;
            }
        </style>
    </head>

    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <div class="main-content-0">
            <?php include "./include/cards.php"; ?>
            <div class='col-md-12 mb-3'> 
                <div class='btn-group'>
                    <button onclick='btnReservas()' id='btn-reservas' class="btn-vendas btn btn-sm btn-dark-blue" disabled> Reservas </button>
                    <button onclick='btnVendas()' id='btn-vendas' class="btn btn-sm btn-dark-blue"> Vendas </button>
                </div>
            </div>
            <?php include "include/vendas.php"; ?>
            <?php include "include/reservas.php"; ?>
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
        <script src="js/financeiro.js"></script>
        <script src="<?= BASED ?>/assets/js/main.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>    
    </body>

</html>