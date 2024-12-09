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
        <link href="<?= BASED ?>/assets/vendor/bootstrap-colorpicker/bootstrap-colorpicker.css" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Frigobar</title>
        <style>
            .btn-icons{
                padding: 10px;
                color: black;
            }
            .btn-acao{
                cursor: pointer;
                margin: 0 5px;
                color: black
            }
        </style> 
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <button class="btn btn-dark-blue btn-sm mt-4" onclick="modalFrigobar()"> <i class="far fa-plus-square"></i> Novo Frigobar </button>
        <div class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="card shadow mt-3">
            <div class="card-header text-center">
                <h4> Frigobar </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive" id="tableFrig"> 
                    <table id="datatable" class="table table-sm table-hover table-secondary" style="width:100%">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th class='width-0'> Modelo </th>
                                <th class='width-0'> Patrimônio </th>
                                <th> Acomodação </th>
                                <th class='text-center'> Status </th>
                                <th class="text-center"> Ação </th>
                            </tr>
                        </thead>
                        <tbody id="tbody-frigobar">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Modais-->
        <!--Adicionar Frigobar-->
        <div class = "modal fade" id = "modalFrigobar" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog">
                <div class = "modal-content cModalFrigobar">
                    <div class="modal-header"> 
                        <h2> Carregando... </h2>
                    </div>
                    <div class="modal-footer"> 

                    </div> 
                </div>
            </div>
        </div>

        <!--java scripito-->  
        <script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
        <!--Bootstrap-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script> 
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/bootstrap-colorpicker/bootstrap-colorpicker.js"></script> 
        <!--Mask input-->
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <!--Select2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="<?= BASED ?>/assets/js/main.js"></script>
        <script src="js/frigobar.js"></script>
    </body>
</html>