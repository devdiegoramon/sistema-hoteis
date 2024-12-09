<?php
if (!isset($_POST['idreserva'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

include "../../config/connMysql.php";
include "../../config/config.php";
$sqlDadosReserva = "SELECT entradaprevista,
                           saidaprevista
                    FROM reserva WHERE idreserva = {$_POST['idreserva']}";
$resultDadosReserva = mysqli_query($con, $sqlDadosReserva);
$rowDadosReserva = mysqli_fetch_array($resultDadosReserva);
?>

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Realizar Check-in</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form class="row g-2" id="formulario-check-in" method="POST" action="include/gCheck-in.php">
            <input hidden type="number" name="idreserva" value="<?= $_POST['idreserva'] ?>"> 
            <input hidden type="text" name='entradaprevista' value="<?= $rowDadosReserva[0] ?>"> 
            <input hidden type="text" name="saidaprevista" value="<?= $rowDadosReserva[1] ?>"> 
            <div class="col-md-12">
                <label> Escolha uma opção </label>
                <div class="form-check mt-2">
                    <input onclick="checkinautomatico()" class="form-check-input" type="radio" name="checkin" id="flexRadioDefault1" checked>
                    <label class="form-check-label" for="checkin">
                        Check-in Automático (Recupera a data e hora atuais)
                    </label>
                </div>
                <div class="form-check border-bottom pb-2 mt-2">
                    <input onclick="checkinmanual()" class="form-check-input" type="radio" name="checkin" id="flexRadioDefault2">
                    <label class="form-check-label" for="checkin">
                        Check-in Manual (Digite a data e hora manualmente)
                    </label>
                </div>
                <div class="row mt-4" id="checkin-manual"> 
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label"> Data do check-in  </label>
                        <input type="text" name="data-checkin" class="form-control date datepicker text-center inputcheckin" disabled required value="<?= date('d/m/Y') ?>"> 
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label"> Hora do check-in  </label>
                        <input type="time" name="hora-checkin" disabled required class="form-control form-control-sm inputcheckin">
                    </div>
                </div>
                <div class="col-12">
                    <span class="text-secondary" style="font-size:70%;"> * Apenas é possível realizar check-in manual com no máximo 1 dia de diferença </span>
                </div>
                <div class="col-12 d-flex justify-content-end mt-3"> 
                    <div> 
                        <button class="btn btn-success btn-sm" form="formulario-check-in"> Concluir Check-in </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#checkin-manual').hide();

    function checkinmanual() {
        $('#checkin-manual').show();
        $('.inputcheckin').prop("disabled", false);

    }
    function checkinautomatico() {
        $('#checkin-manual').hide();
        $('.inputcheckin').prop("disabled", true);
    }

    $('.datepicker').datepicker({
        language: "pt-BR",
        format: "dd/mm/yyyy",
        startDate: '-2d',
        endDate: "date",
        todayHighlight: true
    });
</script> 
<script src="<?= BASED ?>/reserva/js/reserva.js"></script>