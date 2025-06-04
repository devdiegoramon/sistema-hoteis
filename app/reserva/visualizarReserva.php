<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";
include "include/cDadosReserva.php";
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
        <link href="<?= BASED ?>/assets/vendor/select2/select2.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/select2/select2-bootstrap-5-theme.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Reserva</title>
        <style>
            .badge-card{ margin-right: 15px; height:20px; }
            input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; }
            input[type=number] { -moz-appearance: textfield; appearance: textfield; }
            .my-shadow{ background-color: white; -webkit-box-shadow: 5px -9px 38px 15px rgba(0,0,0,0.05); box-shadow: 5px -9px 38px 15px rgba(0,0,0,0.05); }
            .my-badge { margin-right: 10px; margin-top: 10px; }
            .modal-open { padding-right: 16px !important; }
            body { overflow-y: scroll !important; }
            .group{ background-color:#5D6D7E; color:white; }
        </style>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <a class="btn btn-sm btn-dark-blue mt-4" href="index.php"> <i class="fa-regular fa-circle-left"></i> Reservas </a>
        
        <div class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header"> 
                <h5 >Dados da reserva Nº <b> <?= $_GET['id'] ?> </b></h5>
            </div>
            <div class="card-body">
                <?php include "include/header.php" ?>

                <form class="row col-md-12"> 
                    <h5> Reserva </h5>
                    <div class="col-md-6 mt-2"> 
                        <label class="form-label"> Cliente </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><i class="fa-solid fa-user"></i></span>
                            <input type="text" value="<?= $rowReserva['nome'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-6 mt-2"> 
                        <label class="form-label"> CPF </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><i class="fa-solid fa-id-card"></i></span>
                            <input type="text" value=" <?= $rowReserva['cpf'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-6 mt-2"> 
                        <label class="form-label"> Acomodação </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><i class="fa-solid fa-house"></i></span>
                            <input type="text" value="<?= $rowReserva[14] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-3 mt-2"> 
                        <label class="form-label"> Número </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><i class="fa-solid fa-hashtag"></i></span>
                            <input type="number"value="<?= $rowReserva['numero'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-3 mt-2"> 
                        <label class="form-label"> Valor da Diária </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><strong> R$ </strong></span>
                            <input type="text" value="<?= converteReal($rowReserva[23]) ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-4 mt-2"> 
                        <label class="form-label"> Entrada prevista </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><i class="fa-solid fa-calendar-check"></i></span>
                            <input type="text" value="<?= dataBrasil($rowReserva[4]) ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-4 mt-2"> 
                        <label class="form-label"> Saida prevista </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><i class="fa-solid fa-calendar-xmark"></i></span>
                            <input type="text" value="<?= dataBrasil($rowReserva[5]) ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-4 mt-2"> 
                        <label class="form-label">Quantidade de hospedes </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><i class="fa-solid fa-users"></i></span>
                            <input type="text" value="<?= $rowReserva['quantidadehospedes'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-12 mt-2"> 
                        <label class="form-label">Observação </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group"><i class="fa-solid fa-comment "></i></span>
                            <input value="<?= $rowReserva['obs'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div <?= $hidden ?> class="card shadow mt-3 mb-4">
             </div>

        <div class="modal fade" id="modalCheckin" tabindex="-1"><div class="modal-dialog"><div class="modal-content" id="cModalCheckin"></div></div></div>
        <div class="modal fade" id="modalDadosCheckin" tabindex="-1"><div class="modal-dialog"><div class="modal-content" id="cModalDadosCheckin"></div></div></div>
        <div class="modal fade" id="modalCheckout" tabindex="-1"><div class="modal-dialog"><div class="modal-content" id="cModalCheckout"></div></div></div>
        <div class="modal fade" id="modalDadosCheckout" tabindex="-1"><div class="modal-dialog"><div class="modal-content" id="cModalDadosCheckout"></div></div></div>

        <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <script src="<?= BASED ?>/assets/vendor/select2/select2.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/date-picker/datepicker.pt-BR.min.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="js/reserva.js"></script>
        
        <script type="text/javascript">
            const BASE_URL_APP = "<?= BASED ?>"; // Usando um nome diferente para evitar conflito se 'BASED' já for usado em reserva.js
        </script>
        
        <script>
            function realizarCheckin(id) {
                $.ajax({
                    url: BASE_URL_APP + '/reserva/include/mCheckin.php',
                    type: 'POST',
                    data: { idreserva: id },
                    success: function(response) {
                        $('#cModalCheckin').html(response);
                        $('#modalCheckin').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro no AJAX ao chamar mCheckin.php: " + status + ", " + error);
                        console.error("URL Tentada:", BASE_URL_APP + '/reserva/include/mCheckin.php');
                        console.error("Resposta do servidor:", xhr.responseText);
                        alert("Erro ao carregar dados do check-in. Verifique o console do navegador (F12).");
                    }
                });
            }

            function realizarCheckout(id, idconsumo) {
                $.ajax({
                    url: BASE_URL_APP + '/reserva/include/mCheckout.php',
                    type: 'POST',
                    data: { idreserva: id, idconsumo: idconsumo },
                    success: function(response) {
                        $('#cModalCheckout').html(response);
                        $('#modalCheckout').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro no AJAX ao chamar mCheckout.php: " + status + ", " + error);
                        console.error("URL Tentada:", BASE_URL_APP + '/reserva/include/mCheckout.php');
                        console.error("Resposta do servidor:", xhr.responseText);
                        alert("Erro ao carregar dados do check-out. Verifique o console do navegador (F12).");
                    }
                });
            }

            function mCancelarReserva() {
                $('#mCancelarReserva').modal('show');
            }

            function mCancelarReservaAdministrador() {
                $('#mCancelarReservaAdministrador').modal('show');
            }
        </script>
    </body>
</html>