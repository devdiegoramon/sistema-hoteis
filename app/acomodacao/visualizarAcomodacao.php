<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";
if (!isset($_GET['id']) or empty($_GET['id'])) {
    header("Location: ../../index.php?text=Sem Acesso&type=1");
}
$idacomodacao = $_GET['id'];
include "include/cVisualizarAcomodacao.php";

if ($rowDadosAc[7] == 's') {
    $statusAc = "Ativo";
} else {
    $statusAc = "Inativo";
}
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
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title></title>
        <style>
            .card-body .active {
                color: black;
            }
            card-body .active::before {
                position:relative;
            }

            .title-conteudo{
                font-family: sans-serif;
                border-bottom: 2px solid rgb(0,0,0,0.1);
                font-size: 1.1rem;
            }

            .page-item.active .page-link {
                background-color: #5D6D7E !important;
                border-color: #34495E !important;
            }
            .page-link {
                font-size: 80%;
                color: black !important;
                border: 1px solid #dee2e6;
                padding:0.125rem 0.65rem;
            }
            div.dataTables_wrapper div.dataTables_info{
                font-size: 80%;
            }

            .modal-open {
                padding-right: 16px !important;
            }

            body {
                overflow-y: scroll !important;
            }

        </style>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <a class="btn btn-sm btn-dark-blue mt-4" href="index.php"> <i class="fa-regular fa-circle-left"></i> Acomodações </a>
        <div class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="col-md-12 card shadow mt-3">
            <input hidden disabled value="<?= $idacomodacao ?>" id="idacomodacao"> 
            <div class="card-header"> 
                <h5 >Dados da acomodacao Nº <b> <?= $_GET['id'] ?> </b></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 p-3">  
                        <div class="title">
                            <h5 class="title-conteudo pb-1"> Acomodação </h5>
                        </div>
                        <form class=" row p-2"> 
                            <div class="col-md-6"> 
                                <label class="form-label"> Nome </label>
                                <input disabled class="form-control form-control-sm" value="<?= $rowDadosAc[2] ?>"> 
                            </div>

                            <div class="col-md-6"> 
                                <label class="form-label"> Tipo </label>
                                <input disabled class="form-control form-control-sm" value="<?= $rowDadosAc[1] ?>"> 
                            </div>

                            <div class="col-md-4 mt-2"> 
                                <label class="form-label"> Número </label>
                                <input disabled class="form-control form-control-sm" value="<?= $rowDadosAc[3] ?>"> 
                            </div>

                            <div class="col-md-4 mt-2"> 
                                <label class="form-label"> Valor diária</label>
                                <input disabled class="form-control form-control-sm" value="R$ <?= converteReal($rowDadosAc[4]) ?>"> 
                            </div>

                            <div class="col-md-4 mt-2"> 
                                <label class="form-label"> Capacidade </label>
                                <input disabled class="form-control form-control-sm" value="<?= $rowDadosAc[5] ?>"> 
                            </div>

                            <div class="col-md-6 mt-2"> 
                                <label class="form-label"> Status </label>
                                <input disabled class="form-control form-control-sm" value="<?= $statusAc ?>"> 
                            </div>

                            <div class="col-md-6 mt-2"> 
                                <label class="form-label"> Disponível </label>
                                <input disabled class="form-control form-control-sm" value="<?= $statusAc ?>"> 
                            </div>

                            <div class="col-md-12 mt-2"> 
                                <label class="form-label"> Descrição </label>
                                <textarea disabled class="form-control form-control-sm" rows="2"> <?= $rowDadosAc[6] ?> </textarea> 
                            </div>

                        </form>
                    </div>
                    <div class="col-md-6 p-3"> 
                        <div class=" title">
                            <h5 class="title-conteudo pb-1"> Garagem </h5>
                        </div>

                        <form method="POST" class="row mt-3" action='include/gVaga.php'>
                            <input hidden readonly name='idacomodacao' class="form-control form-control-sm" value="<?= $idacomodacao ?>"> 
                            <input hidden readonly name='nomeacomodcao' class="form-control form-control-sm" value="<?= $rowDadosAc[2] ?>"> 
                            <label class="form-label" for="add">Adicionar vaga </label>
                            <div class="input-group"> 
                                <select class="form-select form-select-sm" required name="add"> 
                                    <?php
                                    $sqlVaga = "SELECT idvaga,
                                                       numero
                                                FROM estacionamento 
                                                WHERE ativo = 's'
                                                AND idacomodacao IS NULL";
                                    $resultVaga = mysqli_query($con, $sqlVaga);
                                    if (mysqli_num_rows($resultVaga) > 0) {
                                        echo "<option disabled selected> Selecione uma opção </option>";
                                        while ($rowVaga = mysqli_fetch_array($resultVaga)) {
                                            echo "<option value='$rowVaga[0]'> $rowVaga[1] </option>";
                                        }
                                    } else {
                                        echo "<option disabled selected> Sem vagas disponíveis </option>";
                                    }
                                    ?>
                                </select>
                                <button class="btn btn-success btn-sm" name='adicionar'> Adicionar </button>
                            </div>
                        </form>
                        <div class="table-responsive mt-3"> 
                            <table class="table table-striped table-hover table-secondary"> 
                                <thead> 
                                    <tr> 
                                        <td> ID </td>
                                        <td> Número </td>
                                        <td class="text-center"> Status </td>
                                        <td class="text-center"> Remover </td>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <?php
                                    $sqlEstacionamento = "SELECT idvaga,
                                                                 numero,
                                                                 ativo
                                                           FROM estacionamento 
                                                           WHERE idacomodacao = {$idacomodacao}";
                                    $resultEstacionamento = mysqli_query($con, $sqlEstacionamento);
                                    while ($rowEstacionamento = mysqli_fetch_array($resultEstacionamento)) {
                                        if ($rowEstacionamento[2] == 's') {
                                            $btn = $btnAtivo;
                                        } else {
                                            $btn = $btnInativo;
                                        }
                                        ?>
                                        <tr> 
                                            <td> <?= $rowEstacionamento[0] ?> </td>
                                            <td> <?= $rowEstacionamento[1] ?> </td>
                                            <td class="text-center"> <?= $btn ?> </td>
                                            <td class="text-center"> <a href='include/aRemoverVaga.php?idvaga=<?= $rowEstacionamento[0] ?>&idacomodacao=<?= $idacomodacao ?>&nomeacomodacao=<?= $rowDadosAc[2] ?>' title="remover" style="cursor: pointer" class="badge bg-danger"> X </a>  </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shadow card mt-3 mb-4">
            <div class="card-header"> 
                <div class="row d-flex justify-content-between">
                    <div class="col-md-6">
                        <h5>Histórico de reservas</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="float-end"> 
                            <div class="input-daterange input-group" data-provide="datepicker">
                                <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" name="start" id="start">
                                <span class="input-group-addon range-to" style="margin: 0 5px;">até</span>
                                <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" name="end" id="end" data-date-format="dd/mm/yyyy">
                                <a title="pesquisar" class="btn btn-sm bg-blue1 text-white" onclick="pesquisarReservas(<?= $idacomodacao ?>)"> <i class="fa-solid fa-magnifying-glass"></i> Buscar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table style="font-size:85%;" class="table table-secondary table-hover table-striped" id="datatable"> 
                        <thead>
                            <tr>
                                <th title="ID da reserva"> ID </th>
                                <th> Cliente </th>
                                <th> Entrada Prevista </th>
                                <th> Saida Prevista </th>
                                <th class="text-center"> Situação </th>
                                <th class="text-center"> Visualizar </th>
                            </tr>
                        </thead>
                        <tbody class="tbody-dados"> 
                            <?php
                            $sqlReservas = "SELECT r.idreserva,
                                                                   c.nome,
                                                                   r.quantidadehospedes,
                                                                   r.entradaprevista,
                                                                   r.saidaprevista,
                                                                   r.status
                                                            FROM reserva r 
                                                            INNER JOIN cliente c ON (r.idcliente = c.idcliente)
                                                            WHERE idacomodacao = {$idacomodacao}";
                            $resultReservas = mysqli_query($con, $sqlReservas);
                            while ($rowReservas = mysqli_fetch_array($resultReservas)) {
                                ?>
                                <tr> 
                                    <td> <?= $rowReservas[0] ?> </td>
                                    <td> <?= $rowReservas[1] ?> </td>
                                    <td> <?= dataBrasil($rowReservas[3]) ?> </td>
                                    <td> <?= dataBrasil($rowReservas[4]) ?> </td>
                                    <td class="text-center"> <?= statusReserva($rowReservas[5], 1) ?> </td>
                                    <td class="text-center" title="visualizar"> <a href="../reserva/visualizarReserva.php?id=<?= $rowReservas[0] ?>" class="badge-card badge bg-blue1 text-dark"> <i class="fa-solid fa-eye"></i> </a> </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--jquery-->
    <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
    <!--bootstrap js-->
    <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script>
    <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <!--Mask input-->
    <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
    <!--font awesome-->
    <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
    <!--Chart.js-->
    <script src="<?= BASED ?>/assets/vendor/chart/chart.min.js"></script>
    <!--data table-->
    <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
    <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
    <!--date picker-->
    <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
    <script src="<?= BASED ?>/assets/vendor/date-picker/datepicker.pt-BR.min.js"></script>
    <!--js-->
    <script src="<?= BASED ?>/assets/js/main.js"></script>
    <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
    <script src="js/visualizarAcomodacao.js"></script>
</body>

</html>