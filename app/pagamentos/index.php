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
        <!--data table-->
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <!--select 2-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
        <!--date picker-->
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <!--styles-->
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <!--icons-->
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Pagamentos</title>
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
        <button class="btn btn-dark-blue btn-sm mt-4"  data-bs-toggle="modal" data-bs-target="#modalPagamentos"> <i class="far fa-plus-square"></i> Formas de pagamento </button>
        <div class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="card shadow mt-3">
            <div class="card-header text-center">
                <h4> Comprovantes de pagamento</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive"> 
                    <table id="datatable" class="table table-striped table-secondary table-hover" style="width:100%; font-size: 90%">
                        <thead>
                            <tr>
                                <th> Cód.Comprovante </th>
                                <th> Nº Reserva</th>
                                <th> Cliente </th>
                                <th> Acomodação </th>
                                <th class="text-center width-0"> Visualizar </th>
                                <th class="text-center width-0"> Baixar </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = 'select c.nome,
                                               r.idreserva,
                                               a.nome,
                                               con.comprovantepagamento
                                            from reserva r 
                                            inner join cliente c on (r.idcliente = c.idcliente)
                                            inner join acomodacao a on (r.idacomodacao = a.idacomodacao)
                                            inner join consumo con on (con.idreserva = r.idreserva)
                                        where r.status = "f"
                                    ';
                            $result = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_array($result)) {
                                $comprovante = explode('.', $row[3])[0];
                                ?>  <tr> 
                                    <td> <?= $comprovante ?></td>
                                    <td> <?= $row[1] ?></td>
                                    <td> <?= $row[0] ?></td>
                                    <td> <?= $row[2] ?></td>
                                    <td class='text-center'>
                                        <a class='btn-acao bg-blue badge text-black' href='../comprovantes/pagamentos/<?= $row[3] ?>' target='_blank' > <i class='fa-solid fa-eye'></i> </a>
                                    </td>
                                    <td class='text-center'>
                                        <a class='btn-acao bg-success badge text-white' href='../comprovantes/pagamentos/<?= $row[3] ?>' download='<?= $comprovante ?>.pdf' target='_blank' > <i class='fa-solid fa-download'></i> </a>
                                    </td>
                                </tr>
                            <?php }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Modais-->
        <?php include "./include/mPagamentos.php"; ?>

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
        <!--datepicker-->
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <!--Select2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="<?= BASED ?>/assets/js/main.js"></script>
        <script src="js/pagamentos.js"></script>
    </body>
</html>