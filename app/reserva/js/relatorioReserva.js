function voltar() {
    window.history.back();
}

consultaDados('0', '0');
var contGrafico = 0;
$('.date').mask('00/00/0000');
$('#datatable').DataTable({
    retrieve: true,
    destroy: true,
    "language": {"url": '../assets/vendor/data-table/datatable_pt_br.json'}
});
$('.datepicker').datepicker({
    dateFormat: "dd/mm/yy",
    language: 'pt-BR'
});

//Ranged de dados
function pesquisarReservas() {
    let start = $('#start').val();
    let end = $('#end').val();
    let url = 'include/cReservas.php';

    if (start !== '' && end !== '') {
        $.ajax({
            url: url,
            type: 'POST',
            data: {start, end},
            dataType: 'html',
            success: function (data) {
                $('.tbody-dados').html(data);
            },
            error: function (xhr, er, index, anchor) {
                $('.tbody-dados').html(xhr);
            }
        });
        contGrafico = 1;
        consultaDados(start, end);
    }
}

//Grafico
function consultaDados(start, end) {
    let url = "include/cGraficoRelatorio.php";
    let seguranca = 'seguranca';
    let titulo = "Relatório de reservas";
    if (start !== '0') {
        titulo = "Relatório de reservas entre " + start + " e " + end;
    }

    $.ajax({
        url: url,
        datatype: 'HTML',
        data: {start, end, seguranca},
        type: 'POST',
        success: function (data) {
            let dados = JSON.parse(data)
            let valmax = Math.max.apply(null, dados.quantidade) + 3;
            let labels = dados.acomodacao;
            let valores = dados.quantidade;


            //Graficos
            if (contGrafico == '1') {
                myChart.destroy();
            }
            var ctx = document.getElementById('chart-maisAlugadas').getContext('2d');
            var data = {
                labels: labels,
                datasets: [{
                        label: titulo,
                        //data serve para adicionar o valor de cada barrinha. Basta adicionar a array abaixo:
                        data: valores,
                        barPercentage: .5,
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

