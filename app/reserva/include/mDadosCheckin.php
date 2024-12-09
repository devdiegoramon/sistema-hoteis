<?php
include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";

if (!isset($_POST['idreserva'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}
$idreserva = $_POST['idreserva'];

//DADOS DO CHECK-IN
$sqlDados = "SELECT iduser,
                    obs,
                    json
             FROM log WHERE tabela = 'reserva' 
             AND idtabela = {$idreserva}
             AND acao = 'checkin'";
$resultDados = mysqli_query($con, $sqlDados);
$rowDados = mysqli_fetch_array($resultDados);
$dados = json_decode($rowDados[2], true);
?>

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Dados do check-in</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="table-responsive"> 
            <table class="table table-striped"> 
                <thead> 
                    <tr> 
                        <th rowspan="2"> Dados do check-in </th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td> Realizador por: <td>
                        <td> <?= $dados['nomefuncionario'] ?> </td>
                    </tr>
                    <tr>
                        <td> Dia e hora: <td>
                        <td> <?= dataBrasil($dados['datacheckin']) ?> às <?= substr($dados['horacheckin'], 0, 5) ?>  </td>
                    </tr>
                </tbody>
            </table>
        </div>
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
        startDate: '-1d',
        endDate: "date",
        todayHighlight: true
    });
</script> 
<script src="<?= BASED ?>/reserva/js/reserva.js"></script>