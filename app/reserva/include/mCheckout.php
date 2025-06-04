<?php
require_once "../../config/config.php";
require_once "../../config/connMysql.php";
require_once "../../include/func.php";

if (!isset($_POST['idreserva']) || !isset($_POST['idconsumo'])) {
    header("Location: ../../../index.php?text=Dados incompletos para checkout&type=1");
    exit();
}

$idreserva = (int)$_POST['idreserva'];
$idconsumo_recebido = (int)$_POST['idconsumo'];

$sqlDadosConsumo = "SELECT 
                        c.valorestadia, 
                        c.valoritens, 
                        r.valordiaria, 
                        r.datacheckin, 
                        c.idconsumo 
                    FROM reserva r
                    INNER JOIN consumo c ON c.idreserva = r.idreserva
                    INNER JOIN acomodacao a ON r.idacomodacao = a.idacomodacao
                    WHERE r.idreserva = ?";

$stmtConsumo = mysqli_prepare($con, $sqlDadosConsumo);
if ($stmtConsumo) {
    mysqli_stmt_bind_param($stmtConsumo, "i", $idreserva);
    mysqli_stmt_execute($stmtConsumo);
    $resultDadosConsumo = mysqli_stmt_get_result($stmtConsumo);
    $rowDadosConsumo = mysqli_fetch_array($resultDadosConsumo);
    mysqli_stmt_close($stmtConsumo);
} else {
    error_log("Erro ao preparar consulta de dados de consumo: " . mysqli_error($con));
    echo "<div class='modal-content'><div class='modal-body'><p>Erro ao buscar dados da reserva. Tente novamente.</p></div></div>";
    exit();
}

if (!$rowDadosConsumo) {
    echo "<div class='modal-content'><div class='modal-body'><p>Erro: Reserva ou dados de consumo não encontrados.</p></div></div>";
    exit();
}

$idConsumoReal = (int)($rowDadosConsumo[4] ?? 0);

$sqlItens = "SELECT COUNT(*) as total_itens FROM itensconsumidos WHERE idpedido = ?";
$stmtItens = mysqli_prepare($con, $sqlItens);
$quantidadeItens = 0;

if ($stmtItens) {
    mysqli_stmt_bind_param($stmtItens, "i", $idConsumoReal);
    mysqli_stmt_execute($stmtItens);
    mysqli_stmt_bind_result($stmtItens, $quantidadeItens);
    mysqli_stmt_fetch($stmtItens);
    mysqli_stmt_close($stmtItens);
} else {
    error_log("Erro ao preparar consulta para contar itens consumidos: " . mysqli_error($con));
}

$diarias = 0;
$dataCheckinParaCalculo = $rowDadosConsumo[3];

if (!empty($dataCheckinParaCalculo)) {
    try {
        $dataCheckinObj = new DateTime($dataCheckinParaCalculo);
        $hojeObj = new DateTime(date('Y-m-d'));
        
        if ($dataCheckinObj > $hojeObj) {
            $diarias = 0;
        } else {
            $diff = $dataCheckinObj->diff($hojeObj);
            $diarias = $diff->days;
        }
        if ($diarias == 0 && $dataCheckinObj <= $hojeObj) {
            $diarias = 1;
        }
    } catch (Exception $e) {
        error_log("Erro ao calcular datas: " . $e->getMessage());
        $diarias = 1;
    }
} else {
    $diarias = 1;
}

$valorDiariaReserva = $rowDadosConsumo[2] ?? 0;
$valorTotalDiarias = $diarias * $valorDiariaReserva;
?>

<div class="modal-header">
    <h5 class="modal-title">Realizar Check-out</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row g-2" enctype='multipart/form-data' id="formulario-check-out" method="POST" action="include/gCheck-out.php">
        <input hidden type="number" name="idreserva" value="<?= htmlspecialchars($idreserva) ?>"> 
        <input hidden type="number" name="idconsumo" value="<?= htmlspecialchars($idconsumo_recebido) ?>"> 
        
        <div class="col-md-6"> 
            <label class="form-label"> Total de itens consumidos </label>
            <input readonly class="form-control form-control-sm" type="text" name="itensconsumidos" value="<?= htmlspecialchars($quantidadeItens) ?>"> 
        </div>
        <div class="col-md-6"> 
            <label class="form-label"> Valor total consumido </label>
            <input readonly class="form-control form-control-sm" id="totalconsumido" type="text" name="valor-itens" value="R$ <?= converteReal($rowDadosConsumo[1] ?? 0) ?>"> 
        </div>
        <div class="col-md-6"> 
            <label class="form-label"> Total de diárias </label>
            <input readonly class="form-control form-control-sm" type="text" name="diarias" value="<?= htmlspecialchars($diarias) ?>"> 
        </div>
        <div class="col-md-6"> 
            <label class="form-label"> Valor total da(s) diária(s) </label>
            <input readonly class="form-control form-control-sm" id="totaldiaria" type="text" name="valor-estadia" value="R$ <?= converteReal($valorTotalDiarias) ?>"> 
        </div>
        <div class="form-check form-switch col-md-12 border-bottom pb-2">
            <input onchange="verificaCheckAdicional()" class="form-check-input" type="checkbox" id="checkadd">
            <label class="form-check-label" for="checkadd"> Custos Adicionais </label>
        </div>

        <div id="linha-adicionar" style="display:none;"> 
            <div class="row mt-2 linha" id="linha_1"> 
                <div class="col-5">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">R$</span>
                        <input class="form-control form-control-sm adicional money valor" disabled type="text" name="valor-adicional[]" placeholder="Valor"> 
                    </div>
                </div>
                <div class="col-7"> 
                    <div class="input-group">
                        <input class="form-control form-control-sm adicional" disabled name="descricao-adicional[]" placeholder="Descrição">
                        <button type="button" class="btn btn-secondary btn-sm campo-adicionar-item"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6"> 
            <label class="form-label">Desconto</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">R$</span>
                <input class="form-control form-control-sm money" name="valordesconto" type="text" id="valor_desconto" value="0,00">
            </div>
        </div>
        <div class="col-md-6"> 
            <label class="form-label">Valor Final</label>
            <input class="form-control form-control-sm" name="totalgeral" id="totalgeral" readonly type="text" value="R$ 0,00">
        </div>
        <div class="col-md-12 mt-2"> 
            <label class="form-label"> Forma de pagamento </label>
            <select class="form-select form-select-sm" id='select2_pagamento' required name="formapagamento">
                <?php
                $sqlFormaPagamento = "SELECT nome FROM formapagamento WHERE ativo = 's'";
                $resultFormaPagamento = mysqli_query($con, $sqlFormaPagamento);
                echo "<option value=''>Selecione...</option>";
                if ($resultFormaPagamento && mysqli_num_rows($resultFormaPagamento) > 0) {
                    while ($rowFormaPagamento = mysqli_fetch_array($resultFormaPagamento)) {
                        echo "<option value='" . htmlspecialchars($rowFormaPagamento[0]) . "'> " . htmlspecialchars($rowFormaPagamento[0]) . " </option> ";
                    }
                } else {
                    echo "<option disabled> Nenhuma forma de pagamento disponível </option> ";
                }
                ?>
            </select>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
    <button class="btn btn-success btn-sm" form="formulario-check-out" type="submit">Confirmar Pagamento e Check-out</button> 
</div>

<script>
$(document).ready(function() {
    
    var totalConsumidoFloat = 0;
    var totalEstadiaFloat = 0;

    function carregarValoresIniciais() {
        totalConsumidoFloat = formatarValor($('#totalconsumido').val() ? $('#totalconsumido').val().replace("R$ ", "") : "0,00");
        totalEstadiaFloat = formatarValor($('#totaldiaria').val() ? $('#totaldiaria').val().replace("R$ ", "") : "0,00");
    }

    carregarValoresIniciais();
    maskDinheiro(); 
    calculaTotalGeral(); 
    
    $('#linha-adicionar').hide(); 
    
    var contCheckAdicional = 0; 
    window.verificaCheckAdicional = function() { 
        if ($('#checkadd').is(':checked')) {
            $('#linha-adicionar').show();
            $('#linha_1 .adicional').prop("disabled", false).prop("required", true);
            $('#linha_1 input[name="descricao-adicional[]"]').prop("required", true);
            contCheckAdicional = 1;
        } else {
            $('#linha-adicionar').hide();
            $('#linha-adicionar .adicional').prop("disabled", true).prop("required", false);
            $('#linha-adicionar input[name="valor-adicional[]"]').val('');
            $('#linha-adicionar input[name="descricao-adicional[]"]').val('');
            $('#linha-adicionar .linha:not(#linha_1)').remove(); 
            $('#linha_1 .input-group').find('.campo-adicionar-item, .campo-remover-item')
                .replaceWith('<button type="button" class="btn btn-secondary btn-sm campo-adicionar-item"><i class="fa-solid fa-plus"></i></button>');
            contCheckAdicional = 0;
        }
        calculaTotalGeral();
    }

    // Listener para o campo de desconto e para os campos de valor adicional
    $("#valor_desconto").on('keyup input change', calculaTotalGeral);
    $("#linha-adicionar").on('keyup input change', "input[name='valor-adicional[]']", calculaTotalGeral);
    
    var num_linha_adicional = 1;
    $("#linha-adicionar").on('click', '.campo-adicionar-item', function(){
        num_linha_adicional++;
        let novaLinha = `
            <div class="row mt-2 linha" id="linha_${num_linha_adicional}">
                <div class="col-5">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">R$</span>
                        <input class="form-control form-control-sm adicional money valor" required type="text" name="valor-adicional[]" placeholder="Valor">
                    </div>
                </div>
                <div class="col-7">
                    <div class="input-group">
                        <input class="form-control form-control-sm adicional" required name="descricao-adicional[]" placeholder="Descrição">
                        <button type="button" class="btn btn-danger btn-sm campo-remover-item"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </div>`;
        $('#linha-adicionar .linha:last').after(novaLinha); 
        maskDinheiro(); 
        // Adiciona listener para os novos campos de valor adicionados dinamicamente
        $("#linha_" + num_linha_adicional + " input[name='valor-adicional[]']").on('keyup input change', calculaTotalGeral);
    });

    $("#linha-adicionar").on('click', '.campo-remover-item', function(){
        $(this).closest('.linha').remove();
        calculaTotalGeral();
    });

    function calculaTotalGeral() {
        var totalAdicionais = 0;
        $('input[name="valor-adicional[]"]:enabled').each(function () {
            let valorItem = formatarValor($(this).val());
            totalAdicionais += valorItem;
        });

        let desconto = formatarValor($("#valor_desconto").val()); 
        
        if (isNaN(totalEstadiaFloat) || isNaN(totalConsumidoFloat)) {
            carregarValoresIniciais(); 
        }
        
        let valorFinal = totalEstadiaFloat + totalConsumidoFloat + totalAdicionais - desconto;

        if (valorFinal < 0) {
            valorFinal = 0;
        }
        
        valorFinal = parseFloat(valorFinal.toFixed(2)); 
        let formatado = valorFinal.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        $("#totalgeral").val(formatado);
    }

    function formatarValor(valor) {
        if (typeof valor !== 'string') valor = String(valor || '0'); 
        valor = valor.replace("R$ ", "").replace(/\./g, "").replace(",", ".");
        let parsed = parseFloat(valor);
        return isNaN(parsed) ? 0 : parsed; 
    }

    function maskDinheiro() {
        $('.money').mask('000.000.000.000.000,00', {reverse: true});
    }
});
</script>