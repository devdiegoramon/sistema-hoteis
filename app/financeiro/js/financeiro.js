var contGrafico = 0; //contador de grafico
$('.date').mask('00/00/0000');
$('.datepicker').datepicker({
    language: "pt-BR",
    format: "dd/mm/yyyy",
    todayHighlight: true
});

$('.vendas').hide();
function btnVendas() {
    $('.vendas').show();
    $('.reservas').hide();
    $('#btn-vendas').prop("disabled", true);
    $('#btn-reservas').prop("disabled", false);

}
function btnReservas() {
    $('.vendas').hide();
    $('.reservas').show();
    $('#btn-vendas').prop("disabled", false);
    $('#btn-reservas').prop("disabled", true);
}

//////////////////////////////////////// RESERVAS /////////////////////////////////////
consultaReservas('0', '0');
function rangedReservas() {
    let start = $('#start-reservas').val();
    let end = $('#end-reservas').val();
    consultaReservas(start, end);
}
//Busca de reservas por ranged
function consultaReservas(start, end) {
    if (start && end) {
        let url = "include/cFinanceiroReservas.php";
        let seguranca = 'seguranca';
        $.ajax({
            url: url,
            datatype: 'HTML',
            type: 'POST',
            data: {seguranca, start, end},
            success: function (data) {
                let dados = JSON.parse(data);
                $('.tbody-reservas').html(dados.campos);
                consultaGraficoReservas(dados.start, dados.end);
                $('#datatable').DataTable({
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.4/i18n/pt_br.json"
                    },
                    retrieve: true,
                    paging: true,
                    searching: true
                });
            }
        });
    }
}

contGraficoReserva = 0;
function consultaGraficoReservas(start, end) {
    let url = "include/cGraficoReservas.php";
    $.ajax({
        url: url,
        datatype: 'HTML',
        data: {start, end},
        type: 'POST',
        success: function (data) {
            let dados = JSON.parse(data);
            graficoReservas(dados.label, dados.valor, dados.total, dados.titulo, 'bar', contGraficoReserva);
            contGraficoReserva = 1
        }
    });
}

//GRAFICOS
function graficoReservas(
        nomes,
        valores,
        total,
        titulo,
        type,
        i) {
    $('#valor-graficoRes').html(total);
    if (i == '1') {
        chartReserva.destroy();
    }
    var ctx = document.getElementById('chart-lucroReservas').getContext('2d');
    var data = {
        labels: nomes,
        datasets: [{
                label: titulo,
                //data serve para adicionar o valor de cada barrinha. Basta adicionar a array abaixo:
                data: valores,
                barPercentage: .2,
                backgroundColor: [
                    '#2471A3'
                ]
            }]
    };
    const config = {
        type: type,
        data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        callback: function (value) {
                            return formatar(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            //Dados do tooltip
                            let label = '';

                            if (context.parsed.y !== null) {
                                label += context.parsed.y.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
                            }
                            return label;
                        }
                    }
                }
            }
        }
    };
    chartReserva = new Chart(ctx, config);
}


///////////////////////////////// VENDAS //////////////////////////////////////
consultaVendas('0', '0');
//Verifica se houve busca (0 = não / 1 = sim)

function formatar(valor) {
    valor = parseFloat(valor.toFixed(2));
    let formatado = valor.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
    return formatado;
}

function rangedVendas() {
    let start = $('#start').val();
    let end = $('#end').val();
    consultaVendas(start, end);
}
//Busca Vendas por ranged
function consultaVendas(start, end) {
    if (start && end) {
        let url = "include/cFinanceiroVendas.php";
        let seguranca = 'seguranca';
        $.ajax({
            url: url,
            datatype: 'HTML',
            type: 'POST',
            data: {seguranca, start, end},
            success: function (data) {
                let dados = JSON.parse(data);
                $('.tbody-produtos').html(dados.campos);
                consultaGraficoVendas(dados.start, dados.end);
                $('#datatable-vendas').DataTable({
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.4/i18n/pt_br.json"
                    },
                    retrieve: true,
                    paging: true,
                    searching: true,
                });
            }
        });
    }
}


function consultaGraficoVendas(start, end) {
    let url = "include/cGraficoVendas.php";
    $.ajax({
        url: url,
        datatype: 'HTML',
        data: {start, end},
        type: 'POST',
        success: function (data) {
            let dados = JSON.parse(data);
            let itens = dados.itens;
            let quantidadeitens = dados.quantidade;
            let quantidadetotal = dados.quantidadetotal[0];
            let tituloitens = dados.tituloitens        ;
            
            
            grafico(dados.label, dados.valor, dados.total, dados.titulo, 'line', contGrafico);
            graficoItens(itens, quantidadeitens, quantidadetotal, tituloitens, 'bar', contGrafico);
            contGrafico = 1;
        }
    });
}

//Grafico vendas
function grafico(
        nomes,
        valores,
        total,
        titulo,
        type,
        i) {
    $('#valor-grafico').html(total);
    if (i == '1') {
        myChart.destroy();
    }
    var ctx = document.getElementById('chart-lucroVendas').getContext('2d');
    var data = {
        labels: nomes,
        datasets: [{
                label: titulo,
                lineTension: .1,
                //data serve para adicionar o valor de cada barrinha. Basta adicionar a array abaixo:
                data: valores,
                barPercentage: .4,
                backgroundColor: [
                    '#EB984E'
                ]
            }]
    };
    const config = {
        type: type,
        data,
        options: {
            responsive: true,
            scales: {
                y: {
                    ticks: {
                        callback: function (value) {
                            return formatar(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            //Dados do tooltip
                            let label = '';

                            if (context.parsed.y !== null) {
                                label += context.parsed.y.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
                            }
                            return label;
                        }
                    }
                }
            }
        }
    };
    myChart = new Chart(ctx, config);
}

//Grafico vendas
function graficoItens(
        nomes,
        valores,
        total,
        titulo,
        type,
        i) {
    let valmax = Math.max.apply(null, valores) + 3;
    $('#valor-quantidade').html(total);
    if (i == '1') {
        chartQuantProd.destroy();
    }
    let ctx = document.getElementById('chart-vendidos').getContext('2d');
    var data = {
        labels: nomes,
        datasets: [{
                label: titulo,

                //data serve para adicionar o valor de cada barrinha. Basta adicionar a array abaixo:
                data: valores,
                barPercentage: .1,
                backgroundColor: [
                    '#2471A3'
                ]
            }]

    };
    const config = {
        type: type,
        data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            //Dados do tooltip
                            let label = '';

                            if (context.parsed.y !== null) {
                                label += context.parsed.y + ' vendidos';
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
    chartQuantProd = new Chart(ctx, config);
}

