<?php
// Correção: A ordem dos includes foi ajustada e trocamos para require_once.
require_once "../../config/config.php";
require_once "../../config/connMysql.php";

if (!isset($_POST['idreserva'])) {
    // É uma boa prática sair (exit) após um redirecionamento.
    header("Location: ../../../index.php?text=Sem acesso&type=1");
    exit();
}

$idreserva = $_POST['idreserva'];

$sqlDadosReserva = "SELECT entradaprevista, saidaprevista FROM reserva WHERE idreserva = $idreserva";
$resultDadosReserva = mysqli_query($con, $sqlDadosReserva);
$rowDadosReserva = mysqli_fetch_array($resultDadosReserva);
?>

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Realizar Check-in</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form class="row g-2" id="formulario-check-in" method="POST" action="include/gCheckin.php">
            <input hidden type="number" name="idreserva" value="<?= $idreserva ?>"> 
            <input hidden type="text" name='entradaprevista' value="<?= $rowDadosReserva[0] ?>"> 
            <input hidden type="text" name="saidaprevista" value="<?= $rowDadosReserva[1] ?>"> 
            <div class="col-md-12">
                <label> Escolha uma opção </label>
                <div class="form-check mt-2">
                    <input onclick="checkinautomatico()" class="form-check-input" type="radio" name="checkin" id="checkin-auto" value="auto" checked>
                    <label class="form-check-label" for="checkin-auto">
                        Check-in Automático (Usa a data e hora atuais)
                    </label>
                </div>
                <div class="form-check border-bottom pb-2 mt-2">
                    <input onclick="checkinmanual()" class="form-check-input" type="radio" name="checkin" id="checkin-manual-radio" value="manual">
                    <label class="form-check-label" for="checkin-manual-radio">
                        Check-in Manual (Você digita a data e a hora)
                    </label>
                </div>
                <div class="row mt-4" id="div-checkin-manual"> 
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label"> Data do check-in </label>
                        <input type="text" name="data-checkin" class="form-control date datepicker text-center inputcheckin" disabled required value="<?= date('d/m/Y') ?>"> 
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label"> Hora do check-in </label>
                        <input type="time" name="hora-checkin" disabled required class="form-control form-control-sm inputcheckin">
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <span class="text-secondary" style="font-size:70%;"> * Check-in manual só é permitido com data de ontem, hoje ou amanhã. </span>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
        <button class="btn btn-success btn-sm" form="formulario-check-in" type="submit">Concluir Check-in</button>
    </div>
</div>

<script>
    // Esconde a div de check-in manual por padrão
    $('#div-checkin-manual').hide();

    function checkinmanual() {
        $('#div-checkin-manual').show();
        $('.inputcheckin').prop("disabled", false);
    }
    function checkinautomatico() {
        $('#div-checkin-manual').hide();
        $('.inputcheckin').prop("disabled", true);
    }

    // Inicializa o datepicker para o campo de data
    $('.datepicker').datepicker({
        language: "pt-BR",
        format: "dd/mm/yyyy",
        startDate: '-1d', // Permite selecionar desde ontem
        endDate:   '+1d', // Permite selecionar até amanhã
        todayHighlight: true
    });
</script>