<!DOCTYPE html>

<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";
?>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $iconeSite ?>
        <!--CSS -->
        <link href="<?= BASED ?>/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Estacionamento</title>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <a class="btn btn-sm">  </a>
        <div class="mensagem"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h4> Estacionamento </h4>
            </div>
            <div class="card-body">
                <form id="formulario-estacionamento" autocomplete='off' class="col-md-4" method="POST" action="include/gEstacionamento.php"> 
                    <label class="form-label"> Cadastrar vaga (Número) </label>
                    <div class="input-group"> 
                        <input class="form-control form-control-sm" required type="number" name="numero" min="1" id="numero-acomodacao" placeholder="Número da vaga" title="Número da vaga">
                        <button class="btn btn-sm btn-success" type="submit" name="cadastrar" form="formulario-estacionamento"> Cadastrar </button>
                    </div>
                </form>
                <div class="table-responsive pt-3 mt-3 border-top"> 
                    <table class="table table-sm table-bordered table-hover table-secondary" id='datatable'>
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Número </th>
                                <th> Acomodação </th>
                                <th class='text-center'> Status </th>
                                <?php
                                if ($_SESSION['nivel'] == '1') {
                                    ?>
                                    <th class="text-center"> Excluir </th>
                                <?php }
                                ?>
                            </tr>
                        </thead>
                        <tbody id="tbody-consultaEstacionamento">
                            
                        </tbody> 
                    </table>
                </div> 
            </div>
        </div>
        <!--java scripito -->
        <script src="<?= BASED ?>/assets/bundles/libscripts.bundle.js"></script>
        <script src="<?= BASED ?>/assets/bundles/vendorscripts.bundle.js"></script>
        <!--Bootstrap-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script> 
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <!--date picker-->
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/date-picker/datepicker.pt-BR.min.js"></script>

        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="<?= BASED ?>/assets/js/main.js"></script>
        <script src="js/estacionamento.js"></script>
    </body>

</html>