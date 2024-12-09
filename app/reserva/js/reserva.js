$('#resposta-verificacao').hide();
$('#conteudo-reserva').hide();
$('.checkin-fake').hide();
$('.cpf').mask('000.000.000-00', {reverse: true});
$('.date').mask('00/00/0000');
$('.phone').mask('(00) 00000-0000');
$('.money').mask('000.000,00', {reverse: true});
$('#botao_confirmacao').hide();
$('#form_confirmação').hide();

$('.datepicker').datepicker({
    dateFormat: "dd/mm/yy",
    language: 'pt-BR',
    startDate: 'd'
});

setTimeout(function () {
    $('.mensagem').fadeOut(300, function () {
        $('.mensagem').remove();
    });
}, 5000);


function todoPeriodoRelatorio() {
    let check = $('#todoPeriodo');
    let start = $('#startrel');
    let end = $('#endrel');
    if (check.is(':checked')) {
        start.prop("disabled", true);
        end.prop("disabled", true);
        start.val('');
        end.val('');
    } else {
        start.prop("disabled", false);
        end.prop("disabled", false);
    }
}

function voltar() {
    window.history.back();
}

function modalReserva() {
    $('#modalReserva').modal('show');
    $('.select2').select2({
        width: '100%',
        dropdownParent: $("#modalReserva")
    });
}

function modalRelatorio() {
    $('#modalRelatorio').modal('show');
}

function mCancelarReserva() {
    $('#mCancelarReserva').modal('show');
}

function mCancelarReservaAdministrador() {
    $('#mCancelarReservaAdministrador').modal('show');
}

function abrirConfirmacao() {
    $('#botao_prosseguir').hide();
    $('#botao_confirmacao').show();
    $('#form_confirmação').show();
}

function checkinFake() {
    $('.checkin-fake').fadeIn(500, function () {
        $('.checkin-fake').show();
    });
    setTimeout(function () {
        $('.checkin-fake').fadeOut(300, function () {
            $('.checkin-fake').hide();
        });
    }, 4000);

}

//MODAL DE VISUALIZAR RESERVA 
function visualizarReserva(id) {
    let url = "include/mVisualizarReserva.php"
    $('#modalVisualizar').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {id},
        success: function (data) {
            $('#cModalVisualizar').html(data)
        },
        error: function (xhr, er, index, anchor) {
            $('#cModalVisualizar').html('Error ' + xhr.status);
        }
    });
}

//ADICIONAR CONSUMO 
function itensFrigobar() {
    let idfrigobar = $('#select-frig').val();
    let selectItem = $('#select-item-frig');
    let url = "include/cItensFrigobar.php"
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {idfrigobar},
        success: function (data) {
            selectItem.html(data);
        },
        error: function (xhr, er, index, anchor) {
            selectItem.html('Error ' + xhr.status);
        }
    });

}

function dadosItemFrig() {
    let iditem = $('#select-item-frig').val();
    let quantidade = $('#quantidade-frig');
    let url = "include/cDadosItem.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {iditem},
        success: function (data) {
            let dados = JSON.parse(data);
            quantidade.prop("max", dados.quantidadeMax);
            valorItem = dados.valorunitario;
            quantidadeMax = dados.quantidadeMax;
            $('#label-quantidade').html('Quantidade(Max ' + dados.quantidadeMax + ')');
            $('#quantidade-frig').prop('disabled', false);
        },
        error: function (xhr, er, index, anchor) {
            $('#select-item-frig').html('Error ' + xhr.status);
        }
    });
}

$("#quantidade-frig").keyup(function (e) {
    let quantidade = ($(this).val());
    let valorTotal = quantidade * valorItem;
    valorTotal = parseFloat(valorTotal.toFixed(2))
    let formatado = valorTotal.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
    $('#valorItemFrig').val(formatado);
});


//CHECKIN 
// modal de check-in
function dadosCheckin(idreserva) {
    let url = "include/mDadosCheckin.php";
    $('#modalDadosCheckin').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {idreserva},
        success: function (data) {
            $('#cModalDadosCheckin').html(data);
        },
        error: function (xhr, er, index, anchor) {
            $('#cModalDadosCheckin').html('Error ' + xhr.status);
        }
    });
}

function realizarCheckin(idreserva) {
    let url = "include/mCheck-in.php";
    $('#modalCheckin').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {idreserva},
        success: function (data) {
            $('#cModalCheckin').html(data);
        },
        error: function (xhr, er, index, anchor) {
            $('#cModalCheckin').html('Error ' + xhr.status);
        }
    });
}

//CHECKOUT
function dadosCheckout(idreserva) {
    let url = "include/mDadosCheckout.php";
    $('#modalDadosCheckout').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {idreserva},
        success: function (data) {
            $('#cModalDadosCheckout').html(data);
        },
        error: function (xhr, er, index, anchor) {
            $('#cModalDadosCheckout').html('Error ' + xhr.status);
        }
    });
}

function realizarCheckout(idreserva, idconsumo) {
    let url = "include/mCheck-out.php";
    $('#modalCheckout').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {idreserva, idconsumo},
        success: function (data) {
            $('#cModalCheckout').html(data);
            $('#select2_pagamento').select2({
                width: '100%',
                placeholder: "Selecione uma forma de pagamento",
                allowClear: true,
                "language": {
                    "noResults": function () {
                        return "Nenhuma forma de pagamento disponível.";
                    }
                },
                dropdownParent: $("#modalCheckout")
            });
        },
        error: function (xhr, er, index, anchor) {
            $('#cModalCheckout').html('Error ' + xhr.status);
        }
    });
}