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
consultaFormaPagamento();

$(function () {
    $('#formulario_forma').submit(function (event) {
        return checkFocus();
    });
});

function checkFocus() {
    if ($('#nome_forma').is(':focus')) {
        return false;
    }
    return true;
}

$("#cadastrar_formaPagamento").on("click", function () {
    var nome = $("#nome_forma").val();
    $("#resposta").html("Enviando...");
    $.ajax({
        url: "include/gFormaPagamento.php",
        type: "post",
        data: {nome},
        success: function (data) {
            $("#resposta").html(data);
            consultaFormaPagamento();
            $("#nome_forma").val("");
        }
    });
});

function consultaFormaPagamento() { //CONSULTA TIPO DE ACOMODAÇÕES
    let url = "include/cFormaPagamento.php";
    let seguranca = "seguranca";
    $.ajax({
        url: url,
        type: "post",
        data: {seguranca}
    }).done(function (result) {
        $("#tbody_formaPagamento").html(result);
    });
}

function statusFormaPagamento(id, status, nome) {
    let url = "include/aStatusFormaPagamento.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, nome},
    }).done(function (result) {
        $("#resposta").html(result);
        consultaFormaPagamento();
    });
}

//Editar acomodacao
function editarFormaPagamento(id, nome) {
    var icon = $("#icon-forma" + id).html();
    if (icon !== '<i class="fa fa-check-square-o" aria-hidden="true"></i>') {
        $("#icon-forma" + id).html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
        $("#input-forma" + id).attr("disabled", false);
    } else {
        $("#icon-forma" + id).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
        var input = document.getElementById('input-forma' + id).value;
        let url = "include/aFormaPagamento.php";
        $.ajax({
            url: url,
            dataType: 'html',
            type: 'post',
            data: {id, nome, input},
            beforeSend: function () {
                $("#resposta").html("Enviando...");
                consultaFormaPagamento();
            }
        }).done(function (result) {
            $("#resposta").html(result);
            consultaFormaPagamento();
        });
    }
//    
}