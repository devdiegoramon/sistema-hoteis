consultaTipoAc();
var contconsulta = 0;
consultaAcomodacao();

$('.money').mask('000.000,00', {reverse: true});

$(function () {
    $('#formulario-tipo').submit(function (event) {
        return checkFocus();
    });
});

function checkFocus() {
    if ($('#nome-tipo').is(':focus')) {
        return false;
    }
    return true;
}

//TIPO DE ACOMODACAO
$("#cadastrar-tipo").on("click", function () {
    var nome = $("#nome-tipo").val();
    $("#resposta").html("Enviando...");
    $.ajax({
        url: "include/gTipoAcomodacao.php",
        type: "post",
        data: {nome},
        success: function (data) {
            $("#resposta").html(data);
            consultaTipoAc();
            $("#nome-tipo").val("");
        }
    });
});

function consultaTipoAc() { //CONSULTA TIPO DE ACOMODAÇÕES
    url = "include/cTipoAcomodacao.php";
    let seguranca = "seguranca";
    $.ajax({
        url: url,
        type: "post",
        data: {seguranca}
    }).done(function (result) {
        $("#tbody-tipoAcomodacao").html(result);
    });
}

function statusTipoAcomodacao(id, status, nome) {
    url = "include/aStatusTipoAcomodacao.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, nome},
    }).done(function (result) {
        $("#resposta").html(result);
        consultaTipoAc();
    });
}

//Editar acomodacao
function editarTipoAcomodacao(id, nome) {
    var icon = $("#icon-editarTipo" + id).html();
    if (icon !== '<i class="fa fa-check-square-o" aria-hidden="true"></i>') {
        $("#icon-editarTipo" + id).html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
        $("#input-nomeTipo" + id).attr("disabled", false);
    } else {
        $("#icon-editarTipo" + id).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
        var input = document.getElementById('input-nomeTipo' + id).value;
        url = "include/aTipoAcomodacao.php";
        $.ajax({
            url: url,
            dataType: 'html',
            type: 'post',
            data: {id, nome, input},
            beforeSend: function () {
                $("#resposta").html("Enviando...");
                consultaTipoAc();
            }
        }).done(function (result) {
            $("#resposta").html(result);
            consultaTipoAc();
        });
    }
//    
}

//ACOMODACAO 
function consultaAcomodacao() { //CONSULTA ACOMODAÇÃO
    url = "include/cAcomodacao.php";
    let seguranca = "seguranca";
    $.ajax({
        url: url,
        type: "post",
        data: {seguranca}
    }).done(function (result) {
        var table = $('#datatable').DataTable();
        table.destroy();
        $("#tbody-acomodacao").html(result);
        $('#datatable').DataTable({
            retrieve: true,
            destroy: true,
            "language": {
                "emptyTable": "Nenhum registro encontrado",
                "lengthMenu": "Exibir _MENU_ resultados por página",
                "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                "infoFiltered": "(Filtrados de _MAX_ registros)",
                "infoThousands": ".",
                "zeroRecords": "Nenhum registro encontrado",
                "search": "Pesquisar",
                "paginate": {
                    "next": "Próximo",
                    "previous": "Anterior",
                    "first": "Primeiro",
                    "last": "Último"
                },
                "aria": {
                    "sortAscending": ": Ordenar colunas de forma ascendente",
                    "sortDescending": ": Ordenar colunas de forma descendente"
                }
            }
        });
    });
}

function statusAcomodacao(id, status, nome) { //ALTERA O STATUS DA ACOMODAÇÂO
    url = "include/aStatusAcomodacao.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, nome},
        beforeSend: function () {
            $("#resposta-status").html("Enviando...");
        }
    }).done(function (result) {
        $("#resposta-status").html(result);
        consultaAcomodacao();
    });
}

function excluirAcomodacao(id, nome) {
    url = "include/eAcomodacao.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, nome},
        beforeSend: function () {
            $("#resposta-status").html("Enviando...");
        }
    }).done(function (result) {
        $("#resposta-status").html(result);
        consultaAcomodacao();
    });
}

//MODAIS
function modalTipoAc() {
    url = "include/mCadTipoAc.php";
    $('#modalTipoAc').modal('show');
    let validar = 'validar'
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'GET',
        data: {validar},
        success: function (data) {
            $('.cModalTipoAc').html(data);
        },
        error: function (xhr, er, index, anchor) {
            $('.cModalTipoAc').html('Error ' + xhr.status);
        }
    });
}

function modalAcomodacao() {
    url = "include/mAcomodacao.php";
    $('#modalAcomodacao').modal('show');
    let validar = 'validar'
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'GET',
        data: {validar},
        success: function (data) {
            $('.cModalAcomodacao').html(data);
            $('.select2').select2({
                width: '100%',
                dropdownParent: $("#modalAcomodacao")
            });
        },
        error: function (xhr, er, index, anchor) {
            $('.cModalAcomodacao').html('Error ' + xhr.status);
        }
    });
}

function modalEditarAcomodacao(id) {
    url = "include/mEditarAcomodacao.php";
    $('#modalEditarAcomodacao').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {id},
        success: function (data) {
            $('.cModalEditarAcomodacao').html(data);
        },
        error: function (xhr, er, index, anchor) {
            $('.cModalEditarAcomodacao').html('Error ' + xhr.status);
        }
    });
}

