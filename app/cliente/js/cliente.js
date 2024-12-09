
consultaCliente();
$('.cpf').mask('000.000.000-00', {reverse: true});
$('.date').mask('00/00/0000');
$('.phone').mask('(00) 00000-0000');

$('.datepicker').datepicker({
    language: "pt-BR",
    format: "dd/mm/yyyy",
    endDate: 'd'
});


function todoPeriodo() {
    let check = $('#todoPeriodo');
    let start = $('#start');
    let end = $('#end');
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

//API IBGE
let url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome';

//POPULANDO SELECT (CADASTRAR CLIENTE)
$.getJSON(url, function (data) {
    let estado = '<option disabled selected>Selecione uma opção</option>';
    $.each(data, function (v, val) {
        estado += '<option value="' + val.sigla + '">' + val.nome + '</option>';
    });
    $("#estado").html(estado);
});

$("#estado").on('change', function (e) {
    let uf = ($(this).val());
    let urlCidade = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + uf + '/municipios';
    $.getJSON(urlCidade, function (data) {
        let cidade = '';
        $.each(data, function (v, val) {
            cidade += '<option value="' + val.nome + '">' + val.nome + '</option>';
        });
        $("#cidade").html(cidade);
    });
});

function consultaCliente() { //CONSULTA DADOS DO CLIENTE
    let url = "include/cCliente.php";
    let seguranca = "seguranca";
    $.ajax({
        url: url,
        type: "post",
        data: {seguranca}
    }).done(function (result) {
        var table = $('#datatable').DataTable();
        table.destroy();
        $("#tbody-consultaCliente").html(result);
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

function statusCliente(id, status, nome) { //ALTERA STATUS DO CLIENTE
    url = "include/aStatusCliente.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, nome},
        beforeSend: function () {
            $("#resposta-cliente").html("Enviando...");
        }
    }).done(function (result) {
        $("#resposta-cliente").html(result);
        consultaCliente();
    });
}

//MODAIS
function modalCliente() {
    $('#modalCliente').modal('show');
}

function modalRelatorio() {
    $('#modalRelatorio').modal('show');
}

function modalEditarCliente(id) {
    url = "include/mEditarCliente.php";
    $('#modalEditarCliente').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {id},
        success: function (data) {
            $('.cModalEditarCliente').html(data);
            $('#datepicker').datepicker({
                language: "pt-BR",
                format: "dd/mm/yyyy",
                endDate: 'd'
            });
        },
        error: function (xhr, er, index, anchor) {
            $('.cModalEditarCliente').html('Error ' + xhr.status);
        }
    });
}