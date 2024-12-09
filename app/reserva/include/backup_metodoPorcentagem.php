<?php
include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";

if (!isset($_POST['idreserva'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}

$idreserva = $_POST['idreserva'];
$idconsumo = $_POST['idconsumo'];
$sqlDadosConsumo = "SELECT c.valorestadia,
                           c.valoritens,
                           a.valor,
                           r.datacheckin,
                           c.idconsumo
                    FROM reserva r
                    INNER JOIN consumo c ON (c.idreserva = r.idreserva)
                    INNER JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                    WHERE r.idreserva = {$idreserva}";
$resultDadosConsumo = mysqli_query($con, $sqlDadosConsumo);
$rowDadosConsumo = mysqli_fetch_array($resultDadosConsumo);

//QUANTIDADE DE ITENS CONSUMIDOS
$sqlItens = "SELECT 1 from itensconsumidos WHERE idconsumo = {$rowDadosConsumo[4]}";
$resultItens = mysqli_query($con, $sqlItens);
$quantidadeItens = mysqli_num_rows($resultItens);

$diarias = new DateTime($rowDadosConsumo[3]);
$diarias = $diarias->diff(new DateTime(date('Y-m-d')));
$diarias = $diarias->days; //QUANTIDADES DE DIARIAS

if ($diarias == 0) {
    $diarias = 1;
}

$valorTotalDiarias = $diarias * $rowDadosConsumo[2];
?>

<div class="modal-header">
    <h5 class="modal-title">Realizar Check-out</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row g-2" enctype='multipart/form-data' id="formulario-check-out" method="POST" action="include/gCheck-out.php">
        <input hidden type="number" name="idreserva" value="<?= $_POST['idreserva'] ?>"> 
        <input hidden type="number" name="idconsumo" value="<?= $idconsumo ?>"> 
        <div class="col-md-6"> 
            <label class="form-label"> Total de itens consumidos </label>
            <input readonly class="form-control form-control-sm" type="text" name="itensconsumidos" value="<?= $quantidadeItens ?>"> 
        </div>
        <div class="col-md-6"> 
            <label class="form-label"> Valor total consumido </label>
            <input readonly class="form-control form-control-sm" id="totalconsumido" type="text" name="valor-itens" value="R$ <?= converteReal($rowDadosConsumo[1]) ?>"> 
        </div>
        <div class="col-md-6"> 
            <label class="form-label"> Total de diárias </label>
            <input readonly class="form-control form-control-sm" type="text" name="diarias" value="<?= $diarias ?>"> 
        </div>
        <div class="col-md-6"> 
            <label class="form-label"> Valor total da diaria </label>
            <input readonly class="form-control form-control-sm" id="totaldiaria" type="text" name="valor-estadia" value="R$ <?= converteReal($valorTotalDiarias) ?>"> 
        </div>
        <div class="form-check form-switch col-md-12 border-bottom pb-2">
            <input onchange="verificaCheck()" class="form-check-input" type="checkbox" id="checkadd">
            <label class="form-check-label" for="checkadd"> Custos Adicionais </label>
        </div>

        <div id="linha-adicionar"> 
            <div class="row mt-2 linha" id="linha_1"> 
                <div class="col-5">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">R$</span>
                        <input class="form-control form-control-sm adicional money required valor" disabled type="text" name="valor-adicional[]" placeholder="Valor"> 
                    </div>
                </div>
                <div class="col-7"> 
                    <div class="input-group">
                        <input class="form-control form-control-sm adicional" required disabled name="descricao-adicional[]" placeholder="Descrição"> </textarea>
                        <a class="btn btn-secondary btn-sm campo"><i class="fa-solid fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3"> 
            <label class="form-label"> Desconto </label>
            <div class="input-group input-group-sm ">
                <span class="input-group-text" id="basic-addon1">%</span>
                <input name="porcentagemdesconto" min="0" max='100' value="0" type="number" id='desconto' class="form-control form-control-sm">
            </div>
            <input min="0" hidden type='text' value='0' id='total_desconto' class="form-control form-control-sm">
        </div>
        <div class="col-md-6 mt-3"> 
            <label class="form-label">
                Total de desconto
            </label>
            <input class="form-control form-control-sm money"  name="valordesconto" type="text" id="valor_desconto" value="R$ 00,00" >
        </div>
        <div class="col-md-12"> 
            <label class="form-label">
                Valor Final
            </label>
            <input class="form-control form-control-sm" name="totalgeral" id="totalgeral" readonly type="text" value="00,00">
        </div>
        <div class="col-md-12 mt-2"> 
            <label> Forma de pagamento </label>
            <select class="form-control" select2" id='select2_pagamento' required name="formapagamento"> 
                <?php
                $sqlFormaPagamento = "select nome from formapagamento where ativo = 's'";
                $resultFormaPagamento = mysqli_query($con, $sqlFormaPagamento);
                echo "<option> </option>";
                if (mysqli_num_rows($resultFormaPagamento) > 0) {
                    while ($rowFormaPagamento = mysqli_fetch_array($resultFormaPagamento)) {
                        echo "<option value='$rowFormaPagamento[0]'> $rowFormaPagamento[0] </option> ";
                    }
                } else {
                    echo "<option disabled selected> Nenhuma forma de pagamento disponível </option> ";
                }
                ?>
            </select>
        </div>
        <div class="col-12 d-flex justify-content-end mt-4"> 
            <div> 
                <button class="btn btn-success btn-sm" form="formulario-check-out"> Confirmar Pagamento </button>
            </div>
        </div>
    </form>
</div>

<script>
    $('#checkout-manual').hide();
    $('#linha-adicionar').hide();
    calculaTotal();
    mask();

    //VALORES DE ESTADIA E CONSUMO (FORMATADO PARA CALCULOS)
    var totalconsumido = formatarValor($('#totalconsumido').val().replace("R$ ", ""));
    var totalestadia = formatarValor($('#totaldiaria').val().replace("R$ ", ""));
    var total = formatarValor($('#totalgeral').val().substring(3));

    function zerarDesconto() {
        $("#valor_desconto").val('R$ 00,00');
        i = 0;
        $("#desconto").val('0');
        $("#total_desconto").val('0');
    }

    i = 0;
    $("#desconto").on('keyup', function () {
        if (i === 0) {
            total = formatarValor($('#totalgeral').val().substring(3));
            i++;
        }
        let desconto = $('#desconto').val();
        let valordesconto = 0;

        if (desconto === '' || desconto < 0) {
            desconto = 0;
        } else if (desconto > 100) {
            desconto = 100;
            $('#desconto').val('100');
        }

        valordesconto = total * (desconto / 100);
        $("#total_desconto").val(valordesconto);
        $("#valor_desconto").val(valordesconto.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}));
        calculaTotalGeral();

    });

    //CALCULAR O TOTAL ASSIM QUE ACESSA A PAGINA
    function totalEstadiaConsumo() {
        let valor = totalconsumido + totalestadia;
        let formatado = valor.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        $("#totalgeral").val(formatado);
    }
    totalEstadiaConsumo();

    //MASCARA DE MONEY
    function mask() {
        $('.money').mask('000.000,00', {reverse: true});
    }

    var cont = 0; //contador para verificar se ativaram a checkbox
    function verificaCheck() {
        if (cont === 0) {
            $('#linha-adicionar').show();
            $('.adicional').prop("disabled", false);
            calculaTotalGeral();
            cont = 1;
        } else {
            $('#linha-adicionar').hide();
            $('.adicional').prop("disabled", true);
            totalEstadiaConsumo();
            cont = 0;
        }
    }

    //ADD LINHA
    num_linha = 1; //CONTADOR
    $(".campo").click(function () {
        num_linha++;
        let conteudo = '<div class="row mt-2 linha mb-2" id="linha_' + num_linha + '">' + $(".linha:first").html() + ' </div>';
        $('#linha-adicionar').append(conteudo);
        $('.campo').html('<i class="fa-solid fa-trash"></i>')
        $('.campo:first').html('<i class="fa-solid fa-plus"></i>')
        $('.campo:last').attr('onclick', 'excluirLinha("linha_' + num_linha + '")')
        mask();
        calculaTotal(); //CALCULA O TOTAL
    });

    function excluirLinha(linha) {
        $("#" + linha).remove();
        zerarDesconto();
        //CALCULA O VALOR NOVAMENTE AO EXCLUIR UMA LINHA
        calculaTotalGeral();
    }

    //CALCULA O VALOR TOTAL DE FORMA DINAMICA
    function calculaTotal() {
        $(".valor").on('keyup', function () {
            var valores = 0;
            $('input[name="valor-adicional[]"]').each(function () {
                valor = formatarValor($(this).val());
                if (isNaN(valor)) {
                    valores += parseInt(0);
                } else {
                    valores += valor;
                }
            });
            valores = valores + (totalconsumido + totalestadia);
            total = valores;
            valores = parseFloat(valores.toFixed(2));
            let formatado = valores.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
            $("#totalgeral").val(formatado);

            zerarDesconto();
        });
    }

    //CALCULA TOTAL SEM UTILIZAR O KEYUP
    function calculaTotalGeral() {
        var valores = 0;
        let desconto = $("#total_desconto").val();
        $('input[name="valor-adicional[]"]').each(function () {
            valor = formatarValor($(this).val());
            if (isNaN(valor)) {
                valores += parseInt(0);
            } else {
                valores += valor;
            }
        });
        valores = valores + (totalconsumido + totalestadia);
        valores = valores - desconto;
        valores = parseFloat(valores.toFixed(2));
        let formatado = valores.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        $("#totalgeral").val(formatado);
    }



    function formatarValor(valor) {
        valor = valor.replace(".", "");
        valor = valor.replace(",", ".");
        return parseFloat(valor);
    }

</script> 
<script src="<?= BASED ?>/reserva/js/reserva.js"></script>