consultaFrigobar();

//MODAL PARA CADASTRAR FRIGOBAR
function modalFrigobar() {
    let url = "include/mCadastrarFrigobar.php";
    $('#modalFrigobar').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'GET',
        success: function (data) {
            $('.cModalFrigobar').html(data);
            $('.select2').select2({
                "language": {
                    "noMatches": function () {
                        return "Sem resultados";
                    }},
                width: '100%',
                dropdownParent: $("#modalFrigobar")
            });
        },
        error: function (xhr, er, index, anchor) {
            $('.cModalFrigobar').html('Error ' + xhr.status);
        }
    });
}

//MODAL PARA ADICIONAR NOVOS ITENS
function adicionarItem(id) {
    $('#modalFrigobarAdicionarItem').modal('show');
    let url = "include/mAdicionarItem.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {id},
        success: function (data) {
            $('#cModalAdicionarItem').html(data);

        },
        error: function (xhr, er, index, anchor) {
            $('#cModalAdicionarItem').html('Error ' + xhr.status);
        }
    });
}

//CONSULTA ITEM POR CATEGORIA (MODAL ADICIONAR NOVOS ITENS)
function consultaDadosItem(iditem) {
    $("#select-item").val("");
    $("#estoque").val("");
    $("#select-item").html("<option disabled selected>  Carregando... </option>");
    let url = 'include/cAdicionarItem.php';
    let categoria = $('#select-categoria').val();
    $.ajax({
        url: url,
        type: "post",
        data: {categoria, iditem},
        success: function (data) {
            $("#select-item").html(data);
            if ((data === '<option value=""> Nenhum item cadastrado </option>')) {
                $("#quantidadeItem").prop('disabled', true);
                $("#estoque").prop('disabled', true);
                $("#quantidadeItem").val("");
                $("#estoque").val("");
            } else {
                $("#quantidadeItem").prop('disabled', false);
            }
        }
    });
}

//CONSULTA QUANTIDADE DISPONIVEL EM ESTOQUE
function consultaQuantidade() {
    $("#estoque").val('');
    let iditem = $("#select-item").val();
    if (iditem !== '') {
        let url = "include/cQuantidadeItens.php";
        $.ajax({
            url: url,
            type: "post",
            data: {iditem},
            success: function (data) {
                $("#estoque").val(data);
                estoqueOld = $("#estoque").val();
                $("#quantidadeItem").val("");
                $("#quantidadeItem").prop('disabled', false);
            }
        });
    } else {
        $("#estoque").val('');
    }
    console.log(iditem);
}

//CALCULO DE ITENS DISPONIVEIS NO ESTOQUE
$("#quantidadeItem").keyup(function (e) {
    let adicionar = ($(this).val());
    let novoestoque = estoqueOld - adicionar;
    if (novoestoque <= 0) {
        $('#quantidadeItem').val(estoqueOld);
        $('#estoque').val('0');
    } else {
        $('#estoque').val(novoestoque);
    }
    $(this).attr({
        "max": estoqueOld
    });
});
//CONSULTA FRIGOBAS CADASTRADOS
function consultaFrigobar() {
    url = "include/cFrigobar.php";
    let seguranca = "seguranca";
    $.ajax({
        url: url,
        type: "post",
        data: {seguranca}
    }).done(function (result) {
        var table = $('#datatable').DataTable();
        table.destroy();
        $("#tbody-frigobar").html(result);
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

//CONSULTA ITENS POR FRIGOBAR
function consultaItemFrig(id, frigobar) {
    url = "include/cItemFrigobar.php";
    $.ajax({
        url: url,
        type: "post",
        data: {id, frigobar}
    }).done(function (result) {
        $("#tbody-itemFrig").html(result);
    });
}

//ALTERA STATUS DO FRIGOBAR
function statusFrigobar(id, status, patrimonio) {
    url = "include/aStatusFrigobar.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, patrimonio},
        beforeSend: function () {
            $("#resposta-frigobar").html("Enviando...");
        }
    }).done(function (result) {
        $("#resposta-frigobar").html(result);
        consultaFrigobar();
    });
}

//ALTERA STATUS DO ITEM DO FRIGOBAR
function statusItemFrigobar(id, status, item, frigobar, iditem) {
    url = "include/aStatusItem.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, item, frigobar, iditem},
        beforeSend: function () {
            $("#resposta-item").html("Enviando...");
        }
    }).done(function (result) {
        $("#resposta-item").html(result);
        consultaItemFrig(id, frigobar);
    });
}

//EXCLUI ITEM
function devolverItem(iditem, idfrigobar, nomefrigobar) {
    let url = "include/aDevolverItem.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {iditem, idfrigobar}
    }).done(function (result) {
        $("#resposta-item").html(result);
        consultaItemFrig(idfrigobar, nomefrigobar);
    });
}

//DEVOLVE AO ESTOQUE

//VISUALIZA DADOS DO FRIGOBAR
function visualizarFrigobar(id) {
    let url = "include/cDadosFrigobar.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id},
        success: function (data) {
            $("#tableFrig").hide();
            $("#dadosFrig").html(data);
        },
        function(xhr, er, index, anchor) {
            $('#cModalFrigobar').html('Error ' + xhr.status);
        }
    });
}

function editarFrigobar(id) {
    let url = "include/aFrigobar.php";
    let iconConfirmar = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
    let selectAc = $('#select-acomodacao' + id).html();
    let icon = $('#icon-editar' + id).html();
    let modelo = $('#modeloFrig' + id).val();
    let acomodacao = $('#acomodacaoFrig' + id).val();
    let patrimonio = $('#patrimonioFrig' + id).val();
    if (icon !== iconConfirmar) {
        $("#icon-editar" + id).html(iconConfirmar);
        $("#modeloFrig" + id).attr("disabled", false);
        $("#patrimonioFrig" + id).attr("disabled", false);
        $("#inputAc" + id).html(selectAc);
        $("#select-acomodacao" + id).attr("hidden", false);
    } else {
        $.ajax({
            url: url,
            dataType: 'html',
            type: 'post',
            data: {id, modelo, acomodacao, patrimonio}
        }).done(function (result) {
            $("#resposta-frigobar").html(result);
            consultaFrigobar();
        });
    }
}

