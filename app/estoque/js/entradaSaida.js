$('.datatable').DataTable({
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

$(".adicionar-entrada").keyup(function () {
    let inputAdd = $(this);
    let idAdd = inputAdd.attr('id').split("_");
    let adicionar = $("#add_" + idAdd[1]).val();
    let quantidade = $('#quantEntrada_' + idAdd[1]).val();
    let futura = $('#futuraEntrada_' + idAdd[1]);

    if (adicionar === 0 || adicionar === '') {
        futura.val(quantidade);
    } else {
        let quantidadeFutura = parseInt(quantidade) + parseInt(adicionar);
        futura.val(quantidadeFutura);
    }
});

$(".adicionar-saida").keyup(function () {
    let inputRemove = $(this);
    let idRemove = inputRemove.attr('id').split("_");
    let retirar = $("#rem_" + idRemove[1]).val();
    let quantidade = $('#quantSaida_' + idRemove[1]).val();
    let futura = $('#futuraSaida_' + idRemove[1]);

    if (retirar === 0 || retirar === '') {
        futura.val(quantidade);
    } else {
        if (parseInt(retirar) > parseInt(quantidade)) {
            $("#rem_" + idRemove[1]).val(quantidade);
            futura.val('0');
        } else {
            let quantidadeFutura = parseInt(quantidade) - parseInt(retirar);
            futura.val(quantidadeFutura);
        }

    }
});



