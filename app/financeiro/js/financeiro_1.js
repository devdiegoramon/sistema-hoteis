$('.date').mask('00/00/0000');
$('.datepicker').datepicker({
    language: "pt-BR",
    format: "dd/mm/yyyy",
    todayHighlight: true
});

consultaVendas('0', '0');

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
                
                $('#datatable').DataTable({
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

//Grafico de Vendas
var contGrafico = 0;
function consultaGraficoVendas(start, end) {
    let url = "include/cGraficoVendas.php";
    $.ajax({
        url: url,
        datatype: 'HTML',
        data: {start, end},
        type: 'POST',
        success: function (data) {
            let dados = JSON.parse(data);
            grafico(dados.label, dados.valor, dados.total, dados.titulo, 'line', contGrafico);
            graficoItens(dados.label, dados.quantidade, dados.quantidadetotal[0], dados.tituloquantidade, 'bar', contGrafico);
            contGrafico = 1
        }
    });
}

//GRAFICOS
//Funçoes criadas para facilitar o manuseio dos graficos

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
        console.log('teste');
        myChart.destroy();
    }
    var ctx = document.getElementById('chart-lucroVendas').getContext('2d');
    var data = {
        labels: nomes,
        datasets: [{
                label: titulo,
                //data serve para adicionar o valor de cada barrinha. Basta adicionar a array abaixo:
                data: valores,
                barPercentage: .5,
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
    $('#valor-quantidade').html(total);
    if (i == '1') {
        chartQuantProd.destroy();
    }
    var ctx = document.getElementById('chart-vendidos').getContext('2d');
    var data = {
        labels: nomes,
        datasets: [{
                label: titulo,
                //data serve para adicionar o valor de cada barrinha. Basta adicionar a array abaixo:
                data: valores,
                barPercentage: .5,
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
            }
        }
    };
    chartQuantProd = new Chart(ctx, config);
}