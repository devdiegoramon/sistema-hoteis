$('#datatable').DataTable({
    responsive: true,
    "language": {
        "url": "../assets/vendor/data-table/datatable_pt_br.json"
    }
});

//Visualizar ultimas reservas
function ultimasReservas() {
    let url = "include/cUltimasReservas.php";
    let seguranca = 'seguranca';
    $.ajax({
        url: url,
        datatype: "html",
        type: "POST",
        data: {seguranca},
        success: function (data) {
            document.getElementById('form-baixar').action = 'include/gRelatorioUltimasReservas.php'; //Alterando o action do formulario

            var table = $('#datatable').DataTable();
            table.destroy();
            $('#titulo-card').html("<i class='fa-solid fa-calendar-days mr-3'></i> Reservas nos últimos 7 dias </span>");
            $('#conteudo-card').html(data);
            $('#datatable').DataTable({
                "language": {
                    "url": "../assets/vendor/data-table/datatable_pt_br.json"
                }
            });
        },
        error: function (xhr, er, index, anchor) {
            $('#conteudo-card').html('Error ' + xhr.status);
        }
    });
};

//Visualziar checkin pendentes
function checkinPendentes() {
    let url = "include/cCheckinPendentes.php";
    let seguranca = 'seguranca';
    $.ajax({
        url: url,
        datatype: "html",
        type: "POST",
        data: {seguranca},
        success: function (data) {
            document.getElementById('form-baixar').action = 'include/gRelatorioCheckinPend.php'; //Alterando o action do formulario

            var table = $('#datatable').DataTable();
            table.destroy();
            $('#titulo-card').html("Reservas com <b>check-in pendentes</b>");
            $('#conteudo-card').html(data);
            $('#datatable').DataTable({
                "language": {
                    "url": "../assets/vendor/data-table/datatable_pt_br.json"
                }
            });
        },
        error: function (xhr, er, index, anchor) {
            $('#conteudo-card').html('Error ' + xhr.status);
        }
    });

};
//Visualziar checkout pendentes
function checkoutPendentes() {
    let url = "include/cCheckoutPendentes.php";
    let seguranca = 'seguranca';
    $.ajax({
        url: url,
        datatype: "html",
        type: "POST",
        data: {seguranca},
        success: function (data) {
            document.getElementById('form-baixar').action = 'include/gRelatorioCheckoutPend.php'; //Alterando o action do formulario

            var table = $('#datatable').DataTable();
            table.destroy();
            $('#titulo-card').html("Reservas com <b>check-out pendentes</b>");
            $('#conteudo-card').html(data);
            $('#datatable').DataTable({
                "language": {
                    "url": "../assets/vendor/data-table/datatable_pt_br.json"
                }
            });
        },
        error: function (xhr, er, index, anchor) {
            $('#conteudo-card').html('Error ' + xhr.status);
        }
    });

};

//Visualizar acomodações disponíveis
function acomodacoesDisponiveis() {
    let url = "include/cAcomodacoesDisponiveis.php";
    let seguranca = 'seguranca';
    $.ajax({
        url: url,
        datatype: "html",
        type: "POST",
        data: {seguranca},
        success: function (data) {
            document.getElementById('form-baixar').action = 'include/gRelatorioAcDisponiveis.php'; //Trocando o action do formulario

            var table = $('#datatable').DataTable();
            table.destroy();
            $('#titulo-card').html("Acomodações <b>disponíveis hoje </b>");
            $('#conteudo-card').html(data);
            $('#datatable').DataTable({
                "language": {
                    "url": "../assets/vendor/data-table/datatable_pt_br.json"
                }
            });
        },
        error: function (xhr, er, index, anchor) {
            $('#conteudo-card').html('Error ' + xhr.status);
        }
    });

}

//visualziar acomodações ocupadas
function acomodacoesOcupadas() {
    let url = "include/cAcomodacoesOcupadas.php";
    let seguranca = 'seguranca';
    $.ajax({
        url: url,
        datatype: "html",
        type: "POST",
        data: {seguranca},
        success: function (data) {
            document.getElementById('form-baixar').action = 'include/gRelatorioAcOcupadas.php'; //Trocando o action do formulario
            var table = $('#datatable').DataTable();
            table.destroy();
            $('#titulo-card').html("Acomodações <b> ocupadas hoje </b>");
            $('#conteudo-card').html(data);
            $('#datatable').DataTable({
                "language": {
                    "url": "../assets/vendor/data-table/datatable_pt_br.json"
                }
            });
        },
        error: function (xhr, er, index, anchor) {
            $('#conteudo-card').html('Error ' + xhr.status);
        }
    });

}
