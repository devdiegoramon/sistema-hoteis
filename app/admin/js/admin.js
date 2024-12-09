$('.cpf').mask('000.000.000-00', {reverse: true});
$('.date').mask('00/00/0000');
$('.phone').mask('(00) 00000-0000');

$('.datepicker').datepicker({
    language: "pt-BR",
    format: "dd/mm/yyyy",
    endDate: 'd'
});

$('.toast').show();
consultaFuncionario();

function statusFuncionario(idfuncionario, status, nome) {
    let url = 'include/aStatusFuncionario.php';
    $.ajax({
        url: url,
        dataType: 'HTML',
        type: 'POST',
        data: {idfuncionario, status, nome}
    }).done(function (data) {
        consultaFuncionario();
    });
}

function consultaFuncionario() {
    let url = 'include/cFuncionario.php';
    let seguranca = 'seguranca';
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'post',
        data: {seguranca}
    }).done(function (data) {
        var table = $('#datatable').DataTable();
        table.destroy();
        $('#tbody-funcionario').html(data);
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

//MODAIS
function modalEditarFuncionario(idfuncionario) {
    url = "include/mEditarFuncionario.php";
    $('#modalEditarFuncionario').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        data: {idfuncionario},
        success: function (data) {
            $('.cModalEditarFuncionario').html(data);
        },
        error: function (xhr) {
            $('.cModalEditarFuncionario').html('Error ' + xhr.status);
        }
    });
}

function modalHistorico() {
    url = "include/mHistorico.php";
    $('#modalHistorico').modal('show');
    $.ajax({
        url: url,
        dataType: 'html',
        type: 'POST',
        success: function (data) {
            $('.cModalHistorico').html(data);
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
        },
        error: function (xhr) {
            $('.cModalHistorico').html('Error ' + xhr.status);
        }
    });
}