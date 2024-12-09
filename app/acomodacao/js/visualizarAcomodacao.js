$('.date').mask('00/00/0000');

function voltar() {
    window.history.back();
}

$('#datatable').DataTable({
    "language": {
        "url": "../assets/vendor/data-table/datatable_pt_br.json"
    }
});

function pesquisarReservas(id) {
    let start = $('#start').val();
    let end = $('#end').val();
    let url = 'include/cReservasAcomodacao.php';

    if (start !== '' && end !== '') {
        $.ajax({
            url: url,
            type: 'POST',
            data: {start, end, id},
            dataType: 'html',
            success: function (data) {
                $('.tbody-dados').html(data);
            },
            error: function (xhr, er, index, anchor) {
                $('.tbody-dados').html(xhr);
            }
        });
    }
}

function graficoReservas() {
    let idacomodacao = $('#idacomodacao').val();
    let url = 'include/cDadosGrafico.php';
    $.ajax({
        url: url,
        data: {idacomodacao},
        dataType: 'JSON',
        type: 'POST',
        success: function (data) {
            let valmax = Math.max.apply(null, data.value) + 1;
            var ctx = document.getElementById('chart').getContext('2d');
            var data = {
                labels: data.label,
                datasets: [{
                        label: 'Últimos 12 meses',
                        //data serve para adicionar o valor de cada barrinha. Basta adicionar a array abaixo:
                        data: data.value,
                        barPercentage: .9,
                        backgroundColor: [
                            '#2471A3',
                            '#16A085'
                        ]
                    }]
            };
            const config = {
                type: 'bar',
                data,
                options: {
                    onClick: teste,
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    //Dados do tooltip
                                    let label = 'Total de reservas: ';

                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            suggestedMax: valmax
                        }

                    }
                }
            };
            myChart = new Chart(ctx, config);
        }
    });
}
function teste(evt) {
    var activePoints = myChart.getElementAtEvent(evt);
    alert('Você clicou em ' + activePoints[0]._model.label);
}

