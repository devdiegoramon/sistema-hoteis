<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";

$sql = "SELECT idcliente,
                   nome,
                   cpf,
                   dtnasc,
                   email,
                   telefone,
                   estado,
                   cidade,
                   datag,
                   ativo
            FROM cliente order by nome";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
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
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Clientes</title>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <div class='d-flex justify-content-between'> 
            <button style='margin-right: 20px' class="btn btn-sm btn-dark-blue mt-4" onclick="modalCliente()"> <i class="fa-solid fa-user-plus"></i> Cadastrar Cliente </button>
            <button class="btn btn-sm btn-secondary mt-4" onclick="modalRelatorio()"> <i class="fa-regular fa-file-lines"></i> Relatório de clientes </button>
        </div>
        <div class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="card shadow-sm mt-3">
            <div class="card-header text-center">
                <h4> Clientes </h4>
            </div>
            <div id="resposta"></div>
            <div class="table-responsive p-3">
                <table class="table table-sm table-bordered table-hover table-secondary" id='datatable'>
                    <thead>
                        <tr>
                            <th> # </th>
                            <th > Nome </th>
                            <th style="width:13%;"> CPF </th>
                            <th class='width-0'> <nobr> Data de Nascimento </nobr></th> 
                            <th> Email </th>
                            <th> Telefone </th>
                            <th> Status </th>
                            <th> Editar </th>
                    </tr>
                    </thead>
                    <tbody id="tbody-consultaCliente">

                    </tbody>
                </table>
            </div>
        </div>

        <!--Modal acomodacao-->
        <?php include "./include/mCadastrarCliente.php"; ?>

        <!--modal relatorio-->
        <?php include "./include/mRelatorio.php"; ?>
        <!--Editar Cliente-->
        <div class = "modal fade" id = "modalEditarCliente" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog">
                <div class = "modal-content cModalEditarCliente">
                    <div class="modal-header">
                        <h2> Carregando... </h2>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <!--java scripito :D -->
        <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
        <!--bootstrap-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script>
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <!--jquery mask-->
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <!--datepicker;-->
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/date-picker/datepicker.pt-BR.min.js"></script>
        <!--js-->
        <script src="js/cliente.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="<?= BASED ?>/assets/js/main.js"></script>
    </body>
</html>