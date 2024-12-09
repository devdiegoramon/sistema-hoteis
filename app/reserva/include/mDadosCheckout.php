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
             AND acao = 'checkout'";
$resultDados = mysqli_query($con, $sqlDados);
$rowDados = mysqli_fetch_array($resultDados);
$dados = json_decode($rowDados[2], true);

$sqlConsumo = "SELECT valorestadia,
                      valoritens,
                      valoradicional,
                      valorfinal,
                      totaldesconto,
                      comprovantepagamento
               FROM consumo WHERE idreserva = {$idreserva}";
$resultDadosConsumo = mysqli_query($con, $sqlConsumo);
$rowDadosConsumo = mysqli_fetch_array($resultDadosConsumo);
$rowDadosConsumo[5];
?>

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Dados do check-out</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="table-responsive"> 
            <table class="table table-striped"> 
                <thead> 
                    <tr> 
                        <th style="width:100%;"> Dados do check-out </th>

                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td> Realizador por: <td>
                        <td> <?= $dados['nomefuncionario'] ?> </td>
                    </tr>
                    <tr>
                        <td> Dia e hora: <td>
                        <td> <?= dataBrasil($dados['datacheckout']) ?> às <?= substr($dados['horacheckout'], 0, 5) ?>  </td>
                    </tr>
                    <tr>
                        <td> Valor da estadia: <td>
                        <td> R$ <?= converteReal($rowDadosConsumo[0]) ?>  </td>
                    </tr>
                    <tr>
                        <td> Valor de itens consumidos: <td>
                        <td> R$ <?= converteReal($rowDadosConsumo[1]) ?>  </td>
                    </tr>
                    <tr>
                        <td> Valor adicional: <td>
                        <td> R$ <?= converteReal($rowDadosConsumo[2]) ?>  </td>
                    </tr>
                    <tr>
                        <td> Desconto: <td>
                        <td> R$ <?= converteReal($rowDadosConsumo[4]) ?>  </td>
                    </tr>
                    <tr>
                        <td> Total: <td>
                        <td> R$ <?= converteReal($rowDadosConsumo[3]) ?>  </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12"> 
            <a class='float-end btn bg-blue btn-sm' href='../comprovantes/pagamentos/<?= $rowDadosConsumo[5] ?>' download='<?= $rowDadosConsumo[5] ?>'><i class="fa-solid fa-file-arrow-down"></i> Comprovante </a>
        </div>
    </div>
</div>

<script src="<?= BASED ?>/reserva/js/reserva.js"></script>