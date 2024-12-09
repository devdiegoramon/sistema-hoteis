<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $iconeSite ?>
        <link href="<?= BASED ?>/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/select2/select2.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/select2/select2-bootstrap-5-theme.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Reservas</title>
        <style>
            .badge-card{
                margin-right: 15px;
                height:20px;
            }
            input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;

            }
            input[type=number] {
                -moz-appearance: textfield;
                appearance: textfield;

            }
            .modal-open {
                padding-right: 16px !important;
            }

            body {
                overflow-y: scroll !important;
            }
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function (event) {
                $("#filtro").val(<?= isset($_GET['filtro']) ? $_GET['filtro'] : "" ?>);
                $('.money').mask('000.000,00', {reverse: true});
                changeFiltro()
            });

            function formatarValor(valor) {
                valor = valor.replace(".", "");
                valor = valor.replace(",", ".");
                return parseFloat(valor);
            }

            function resetRange() {
                let selectAc = $('#select-acomodacao');
                let hospedes = $('#hospedes');
                let btnreserva = $('#btn-reserva');
                let obs = $('#obs');

                selectAc.prop("disabled", true);
                hospedes.prop("disabled", true);
                btnreserva.prop("disabled", true);
                obs.prop("disabled", true);

                selectAc.html('');
                hospedes.val('');
                obs.val('');
                $('#valor-estadia').val('');
            }

//Verifica disponibilidade do agendamento
            function verificarDisp() {
                let start = $('#start').val();
                let end = $('#end').val();
                let url = "include/verReservas.php";
                if (start === '' || end === '') {
                    $('#resposta-verificacao').html('Alguma data está vazia');
                    $('#resposta-verificacao').show();
                } else {
                    $.ajax({
                        url: url,
                        dataType: 'html',
                        type: 'POST',
                        data: {start, end},
                        success: function (data) {
                            var dados = JSON.parse(data);
                            $('#resposta-verificacao').html(dados.resposta);
                            if (dados.tipo !== '1' && dados.acomodacoes !== '0') {
                                $('#diarias').val(dados.diarias);
                                $('#select-acomodacao').html(dados.option);
                                $('#select-acomodacao').prop("disabled", false);
                                $('#hospedes').prop("disabled", false);
                                $('#btn-reserva').prop("disabled", false);
                                $('#obs').prop("disabled", false);
                                $('#conteudo-reserva').show();
                            }
                            $('#resposta-verificacao').show();
                        },
                        error: function (xhr, er, index, anchor) {
                            $('#cModalEditarSala').html('Error ' + xhr.status);
                        }
                    });
                }
            }

            function calculaEstadia() {
                let idacomodacao = $('#select-acomodacao').val();
                let start = $('#start').val();
                let end = $('#end').val();
                let url = 'include/cDadosAcomodacao.php'
                let valor = $('#valor-estadia');
                let valorDiarias = $('#valordiaria');
                $.ajax({
                    url: url,
                    dataType: 'html',
                    type: 'POST',
                    data: {idacomodacao, start, end},
                    success: function (data) {
                        var dados = JSON.parse(data);
                        valor.val(dados.valor)
                        $('#label-hospedes').html('Número de Hospedes (Max ' + dados.capacidade + ')')
                        $('#hospedes').prop("max", dados.capacidade);
                        $('#valordiaria').prop("disabled", false);
//                        $('#total').prop("disabled", false);

                        if (valorDiarias > 0) {
                        } else {
                            valorDiarias.val(dados.valordiarias);
                        }
                        calculaValorFinal();
                    },
                    error: function (xhr, er, index, anchor) {
                        $('#cModalEditarSala').html('Error ' + xhr.status);
                    }
                });
            }

            function calculaValorFinal() {
                let dias = $('#diarias').val();
                let hospedes = $("#hospedes").val();
                let valorDiaria = formatarValor($("#valordiaria").val());
                let valorTotal = (dias * valorDiaria);
//                let valorTotal = (dias * valorDiaria) * hospedes;

                if (valorTotal > 0) {
                    valorTotal = valorTotal.toLocaleString('pt-br', {minimumFractionDigits: 2});
                } else {
                    valorTotal = '00,00';
                }
                $('#valor-estadia').val(valorTotal);
            }

            function changeFiltro() {
                let filtro = $("#filtro").val();
                if (filtro == 2) {
                    $("#pesquisaFiltro").mask('000.000.000-00', {reverse: true});
                } else {
                    $("#pesquisaFiltro").unmask();
                }
            }
        </script>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <div class="d-flex justify-content-md-between mb-3 " >
            <button style='margin-right: 20px' class="btn btn-dark-blue btn-sm mt-4" onclick="modalReserva()"> <i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Nova Reserva </button>
            <a class="btn btn-secondary btn-sm mt-4" href="relatorioReserva.php"> <i class="fa-regular fa-file-lines"></i> Relatório de reservas </a>
        </div>
        <div data-bs-toggle="collapse" class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="card text-center shadow-sm bg-white rounded">
            <div class="card-header">
                RESERVAS
            </div>
            <div class="card-body">
                <h5 class="card-title">Todas reservas registradas</h5>
                <div class="row d-flex justify-content-between mt-3"> 
                    <form  id="form-badge" action="index.php" method="GET" class="col-md-6">
                        <button form='form-badge' value="" name="busca" class="border-0 badge bg-secondary badge-card float-start mt-2"> Todas </button>
                        <button form='form-badge' value="p" name="busca" class="border-0 badge bg-warning text-black badge-card float-start mt-2"> Pendente </button>
                        <button form='form-badge' value="i" name="busca" class="border-0 badge bg-blue1 badge-card float-start mt-2"> Iniciado </button>
                        <button form='form-badge' value="f" name="busca" class="border-0 badge bg-success badge-card float-start mt-2"> Finalizado </button>
                        <button form='form-badge' value="c" name="busca" class="border-0 badge bg-danger badge-card float-start mt-2" > Cancelado </button>
                    </form>
                    <div class="col-md-5"> 
                        <form method="GET" class="row d-flex justify-content-md-end justify-content-sm-start"> 
                            <!--                            <div class="col-md-4 mt-2" >
                                                            <select name="busca" class="form-select form-select-sm"> 
                                                                <option value="todas" selected> Todas </option>
                                                                <option <?= isset($_GET['busca']) AND $_GET['busca'] == 'p' ? 'selected' : ''; ?> value="p"> Pendente </option>
                                                                <option <?= isset($_GET['busca']) AND $_GET['busca'] == 'i' ? 'selected' : ''; ?> value="i"> Iniciado </option>
                                                                <option <?= isset($_GET['busca']) AND $_GET['busca'] == 'f' ? 'selected' : ''; ?> value="f"> Finalizado </option>
                                                                <option <?= isset($_GET['busca']) AND $_GET['busca'] == 'c' ? 'selected' : ''; ?> value="c"> Cancelado </option>
                                                            </select>
                                                        </div>-->
                            <div class="col-md-4 mt-2" >
                                <?php
                                if (isset($_GET['busca'])) {
                                    echo "<input hidden value='{$_GET['busca']}' name='busca'>";
                                }
                                ?>
                                <select name="filtro" class="form-select form-select-sm" id='filtro' onchange='changeFiltro()'> 
                                    <option value="1"> Nome do cliente </option>
                                    <option value="2"> Cpf do cliente </option>
                                    <option value="3"> Nome do quarto </option>
                                    <option value="4"> Número do quarto </option>
                                    <option value="5"> Número da reserva </option>
                                </select>
                            </div>
                            <div class="col-md-8 mt-2" >
                                <div class="input-group">
                                    <input class="form-control form-control-sm"  placeholder="Pesquisar" id='pesquisaFiltro' name="buscafiltro"> 
                                    <button class="btn btn-dark-blue btn-sm"> Buscar </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2"> 
            <?php
            //CONFIÇÃO PARA BUSCA POR MEIO DO GET
            $condicao = '';
            if (!empty($_GET['busca'])) {
                $busca = $_GET['busca'];
                if ($busca == 'todas') {
                    $condicao = '';
                } else {
                    $condicao = "WHERE status = '$busca'";
                }
            }

            if (isset($_GET['filtro'])) {
                $filtro = $_GET['filtro'];
                $buscaFiltro = $_GET['buscafiltro'];
                $param = !empty($condicao) ? "AND" : "WHERE";

                switch ($filtro) {
                    case 1:
                        $condicao .= "$param c.nome LIKE '$buscaFiltro%'";
                        break;
                    case 2:
                        $condicao .= "$param c.cpf  LIKE '$buscaFiltro%'";
                        break;
                    case 3:
                        $condicao .= "$param a.nome  LIKE '$buscaFiltro%'";
                        break;
                    case 4:
                        $condicao .= "$param a.numero  LIKE '$buscaFiltro%'";
                        break;
                    case 5:
                        $condicao .= "$param r.idreserva  LIKE '$buscaFiltro%'";
                        break;
                    default :
                        $condicao .= '';
                        break;
                }
            }

            $sqlReservas = "SELECT r.idreserva,
                                        r.idacomodacao,
                                        r.idcliente,
                                        r.quantidadehospedes,
                                        r.entradaprevista,
                                        r.saidaprevista,
                                        r.status,
                                        r.obs,
                                        a.nome,
                                        c.nome,
                                        t.nome,
                                        a.numero
                                FROM reserva r        
                                INNER JOIN cliente c ON (r.idcliente = c.idcliente)
                                INNER JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                                INNER JOIN tipoacomodacao t ON (a.idtipoacomodacao = t.idtipoac)
                                $condicao
                                ORDER BY r.idreserva DESC";
            $resultReservas = mysqli_query($con, $sqlReservas);
            if (mysqli_num_rows($resultReservas) > 0) {
                while ($rowReservas = mysqli_fetch_array($resultReservas)) {
                    if ($rowReservas[6] == 'c') {
                        $class = 'bg-danger text-white';
                    } elseif ($rowReservas[6] == 'i') {
                        $class = 'bg-blue1 text-white';
                    } elseif ($rowReservas[6] == 'f') {
                        $class = 'bg-success text-white';
                    } else {
                        $class = 'bg-warning';
                    }
                    ?> 
                    <div class="col-lg-3 col-md-6 col-sm-12 p-3"> 
                        <div class="card shadow-sm border" style="border-radius:0.2em; height: 100%;">
                            <div class="card-body p-0">
                                <div class="<?= $class ?>" style="padding:10px 10px;">
                                    <span> <i class="fa-solid fa-house-chimney"></i>  <?= $rowReservas[8] ?>  <small style="vertical-align:top;">Nº<?= $rowReservas[11] ?></small>  </span> <br>

                                </div>
                                <div class="row p-3">
                                    <div class="col-12 " style="height:80%; font-size:.9em;"> 
                                        <span><i class="fa-solid fa-calendar-week"></i> Reserva Nº<?= $rowReservas[0] ?> </span> <br>
                                        <!--<span><i class="fa-solid fa-house-chimney"></i> <?= $rowReservas[8] ?> - Nº <?= $rowReservas[11] ?> </span> <br>-->
                                        <span><i class="fa-solid fa-user"></i> <?= $rowReservas[9] ?>  </span> <br>
                                        <span> 
                                            <i class="fa-solid fa-calendar-day"></i>
                                            <?= dataBrasil($rowReservas[4]) ?>
                                            -
                                            <?= dataBrasil($rowReservas[5]) ?>
                                        </span>
                                    </div>
                                    <div class="col-12 mt-2" style="height:20%;"> 
                                        <a style="border-radius: 2px" href="visualizarReserva.php?id=<?= $rowReservas[0] ?>" class="col-12 btn btn-dark-blue btn-sm">Visualizar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?> 
                <div class="row text-center mt-5"> 
                    <h5>Nenhum registro encontrado</h5>
                </div>
                <?php
            }
            ?> 
        </div>
        <!--modais-->
        <!--Adicionar Reserva-->
        <?php include "include/mCadastrarReserva.php" ?>
        <!-- Visualizar Agendamento -->
        <div class="modal fade" id="modalVisualizar" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content" id="cModalVisualizar">
                    <div class="modal-header"> 
                        <h2> Carregando... </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer"> 

                    </div> 
                </div>
            </div>
        </div>

        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <!--jquery-->
        <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
        <!--bootstrap js-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script>
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <!--Mask input-->
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--Select 2-->
        <script src="<?= BASED ?>/assets/vendor/select2/select2.min.js"></script>
        <!--date picker-->
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/date-picker/datepicker.pt-BR.min.js"></script>
        <!--js-->
        <script src="js/reserva.js"></script>
        <!--<script src="<?= BASED ?>/assets/js/main.js"></script>-->
    </body>

</html>