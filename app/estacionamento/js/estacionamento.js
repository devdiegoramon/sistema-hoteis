consultaEstacionamento();

function consultaEstacionamento() { //CONSULTA DADOS DO CLIENTE
    url = "include/cEstacionamento.php";
    let seguranca = "seguranca";
    $.ajax({
        url: url,
        type: "post",
        data: {seguranca}
    }).done(function (result) {
        var table = $('#datatable').DataTable();
        table.destroy();
        $("#tbody-consultaEstacionamento").html(result);
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

function statusEstacionamento(id, status, numero) { //ALTERA STATUS DO CLIENTE
    url = "include/aStatusEstacionamento.php";
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {id, status, numero},
        beforeSend: function () {
            $("#resposta-consultaEstacionamento").html("Enviando...");
        }
    }).done(function (result) {
        $("#resposta-consultaEstacionamento").html(result);
        consultaEstacionamento();
    });
}