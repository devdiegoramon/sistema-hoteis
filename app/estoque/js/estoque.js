consultaCategoria();
consultaItem();

$('.money').mask('000.000,00', {reverse: true});

//CATEGORIA
//editar categoria
function editarCategoria(id, nome) {
    var icon = $("#icon-categoria" + id).html();
    if (icon !== '<i class="fa fa-check-square-o" aria-hidden="true"></i>') {
        $("#icon-categoria" + id).html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
        $("#input-nomeCategoria" + id).attr("disabled", false);
    } else {
        $("#icon-categoria" + id).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
        var input = document.getElementById('input-nomeCategoria' + id).value;
        url = "include/aCategoria.php";
        $.ajax({
            url: url,
            dataType: 'html',
            type: 'post',
            data: {id, nome, input},
            beforeSend: function () {
                $("#resposta-categoria").html("Enviando...");
                consultaCategoria();
            }
        }).done(function (result) {
            $("#resposta-categoria").html(result);
            consultaCategoria();
        });
    }
}

$(function () { //Desativa o enter
    $('#formulario-categoria').submit(function (event) {
        return checkFocus();
    });
});

function checkFocus() {
    if ($('#nome-categoria').is(':focus')) {
        return false;
    }
    return true;
}

function modalCategoria() {
    url = "include/mCadCategoria.php";
    $('#modalCategoria').modal('show');
    let validar = 'validar';
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'GET',
        data: {validar},
        success: function (data) {
            $('#modalCategoria').html(data);
        },
        error: function (xhr, er, index, anchor) {
            $('#cModalCategoria').html('Error ' + xhr.status);
        }
    });
}


$("#cadastrar-categoria").on("click", function () {
    var nome = $("#nome-categoria").val();
    $("#resposta-categoria").html("Enviando...");
    $.ajax({
        url: "include/gCategoria.php",
        type: "post",
        data: {nome},
        success: function (data) {
            $("#resposta-categoria").html(data);
            consultaCategoria();
            $("#nome-categoria").val("");
        }
    });
});

function consultaCategoria() { //CONSULTA TIPO DE ACOMODAÇÕES
    url = "include/cCategoria.php";
    let seguranca = "seguranca";
    $.ajax({
        url: url,
        type: "post",
        data: {seguranca}
    }).done(function (result) {
        $("#tbody-categoria").html(result);
    });
}

function statusCategoria(id, status, nome) {
    url = "include/aStatusCategoria.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, nome},
        beforeSend: function () {
            $("#resposta-categoria").html("Enviando...");
        }
    }).done(function (result) {
        $("#resposta-categoria").html(result);
        consultaCategoria();
    });
}
// FIM CATEGORIA 

//ITENS

function modalItem() {
    url = "include/mCadItem.php";
    $('#modalItem').modal('show');
    let validar = 'validar';
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'GET',
        data: {validar},
        success: function (data) {
            $('.cModalItem').html(data);
        },
        error: function (xhr, er, index, anchor) {
            $('.cModalItem').html('Error ' + xhr.status);
        }
    });
}

function editarItem(id) {
    let iconConfirmar = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
    let selectCat = $('#select-categoria' + id).html();
    let icon = $('#icon-editarItem' + id).html();
    let url = "include/aItem.php";
    let nome = $('#nomeItem' + id).val();
    let categoria = $('#categoria' + id).val();
    let valor = $('#valorItem' + id).val();
    if (icon !== iconConfirmar) {
        $('#valorItem' + id).mask('000.000,00', {reverse: true});
        $("#icon-editarItem" + id).html(iconConfirmar);
        $("#nomeItem" + id).attr("disabled", false);
        $("#valorItem" + id).attr("disabled", false);
        $("#input-categoriaItem" + id).html(selectCat);
        $("#select-categoria" + id).attr("hidden", false);
    } else {
        $.ajax({
            url: url,
            dataType: 'html',
            type: 'post',
            data: {id, nome, categoria, valor}
        }).done(function (result) {
            $("#resposta-item").html(result);
            consultaItem();
        });
    }
}

function modalEditarItem(id) {
    url = "include/mEditarItem.php";
    $('#modalEditarItem').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        data: {id},
        type: 'POST',
        success: function (data) {
            $('#modalEditarItem').html(data);
        },
        error: function (xhr, er, index, anchor) {
            $('#cModalEditarItem').html('Error ' + xhr.status);
        }
    });
}

function consultaItem() { //CONSULTA TIPO DE ACOMODAÇÕES
    url = "include/cItem.php";
    let seguranca = "seguranca";
    $.ajax({
        url: url,
        type: "post",
        data: {seguranca}
    }).done(function (result) {
        var table = $('#datatable').DataTable();
        table.destroy();
        $("#tbody-item").html(result);
        $('#datatable').DataTable({
            retrieve: true,
            destroy: true,
            searching: false,
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

function statusItem(id, status, nome) {
    url = "include/aStatusItem.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, nome},
        beforeSend: function () {
            $("#resposta-item").html("Enviando...");
        }
    }).done(function (result) {
        $("#resposta-item").html(result);
        consultaItem();
    });
}

//FIM ITENS