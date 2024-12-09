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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Acomodações</title>
        <style>
            .btn-acao{
                cursor: pointer;
                margin: 0 5px;
                color: black
            }
        </style>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <div class="d-flex justify-content-md-start">
            <button style='margin-right: 20px' class="btn btn-dark-blue btn-sm mt-4"  onclick="modalTipoAc()"> <i class="fas fa-search-plus"></i> Tipo de acomodação </button>
            <button class="btn btn-dark-blue btn-sm mt-4" onclick="modalAcomodacao()"> <i class="far fa-plus-square"></i> Acomodação </button>
        </div>
        <div class="mensagem mt-3">
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="card shadow mt-3">
            <div class="card-header text-center">
                <h4> Acomodações </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive"> 
                    <table class="table table-sm table-hover table-secondary" id='datatable'>
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Nome </th>
                                <th> Número </th>
                                <th> Tipo </th>
                                <th> Valor </th>
                                <th> Capacidade </th>
                                <th> Descricao </th>
                                <th> Status </th>
                                <th class="text-center"> Ação </th>
                            </tr>
                        </thead>
                        <tbody id="tbody-acomodacao">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--Tipo acomodação-->
        <div class = "modal fade" id = "modalTipoAc" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog">
                <div class = "modal-content cModalTipoAc">
                    <div class="modal-header"> 
                        <h2> Carregando... </h2>
                    </div>
                    <div class="modal-footer"> 

                    </div> 
                </div>
            </div>
        </div>
        <!--Adicionar Acomodação-->
        <div class = "modal fade" id = "modalAcomodacao" aria-hidden = "true">
            <div class = "modal-dialog">
                <div class = "modal-content cModalAcomodacao">
                    <div class="modal-header"> 
                        <h2> Carregando... </h2>
                    </div>
                    <div class="modal-footer"> 

                    </div> 
                </div>
            </div>
        </div>
        <!--Editar Acomodação-->
        <div class = "modal fade" id = "modalEditarAcomodacao" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog">
                <div class = "modal-content cModalEditarAcomodacao">
                    <div class="modal-header"> 
                        <h2> Carregando... </h2>
                    </div>
                    <div class="modal-footer"> 

                    </div> 
                </div>
            </div>
        </div>

        <script src = "https://code.jquery.com/jquery-3.5.1.js"></script>
        <!--java scripito-->  
        <!--Bootstrap-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script> 
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <!--Mask input-->
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--Select2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>

        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <!--<script src="<?= BASED ?>/assets/js/main.js"></script>-->
        <script src="js/acomodacao.js"></script>
    </div>
</body>
</html>