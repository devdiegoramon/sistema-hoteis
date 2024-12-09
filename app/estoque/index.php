<!DOCTYPE html>

<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";

$activeItens = 'show active';
$activeEntrada = '';
$activeSaida = '';
if (isset($_SESSION['tipoEstoque'])) {
    if ($_SESSION['tipoEstoque'] == 'entrada') {
        $activeItens = '';
        $activeSaida = '';
        $activeEntrada = 'show active';
    } else {
        $activeItens = '';
        $activeEntrada = '';
        $activeSaida = 'show active';
    }
}
?>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $iconeSite ?>
        <!--CSS -->
        <link href="<?= BASED ?>/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Estoque</title>
        <style>
            .card-body .active {
                color: black;
            }
            .card-body .active::before {
                position:relative;
            }
            .btn-del{
                cursor:pointer;
            }
            .btn-add{
                cursor:pointer;
                margin-left: 15px;
                font-size: 0.8rem;
                color:black !important;
                padding: 2px 10px;
                border-radius: 4px;
                border:1px solid #1F618D ;
            }
            .btn-add:hover{
                transition: 0.3s ease;
                background-color: #1F618D;
                color:white !important;
            }

            input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;

            }
            input[type=number] {
                -moz-appearance: textfield;
                appearance: textfield;
            }
            .zebrar:nth-child(odd) {
                background: #f5f2f2;
            }
            .nav-tabs .nav-link {
                color: #084298 !important;
            }

        </style>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <button class="btn btn-dark-blue btn-sm mt-4" onclick="modalCategoria()"> <i class="fa fa-search-plus" aria-hidden="true"></i> Categorias </button>
        <button class="btn btn-dark-blue btn-sm mt-4" onclick="modalItem()"> <i class="fa fa-cart-plus" aria-hidden="true"></i> Adicionar Item </button>
        <div class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="card shadow mt-3">
            <div class="card-header text-black">
                <span> Estoque </span>
            </div>
            <div class="card-body"> 
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= $activeItens ?>" id="home-tab" data-bs-toggle="tab" data-bs-target="#itens" type="button" role="tab" aria-controls="itens" aria-selected="true">Itens</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $activeEntrada ?>" id="profile-tab" data-bs-toggle="tab" data-bs-target="#entrada" type="button" role="tab" aria-controls="entrada" aria-selected="false">Adicionar Entrada</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $activeSaida ?>" id="contact-tab" data-bs-toggle="tab" data-bs-target="#saida" type="button" role="tab" aria-controls="saida" aria-selected="false">Adicionar Saida</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade <?= $activeItens ?>" id="itens" role="tabpanel" aria-labelledby="itens-tab">
                        <div id="resposta-item mt-2"> </div>
                        <div class="table-responsive mt-4">
                            <table style="font-size:90%;" id="datatable" class="table table-hover table-secondary table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:0%;"> # </th>
                                        <th> Item </th>
                                        <th> Categoria </th>
                                        <th> Quantidade </th>
                                        <th> Preço (Unitário) </th>
                                        <th class="width-0 text-center"> Status </th>
                                        <th class="width-0 text-center"> Editar </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-item">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade <?= $activeEntrada ?>" id="entrada" role="tabpanel" aria-labelledby="entrada-tab">
                        <form action="include/gEntrada.php" method="POST"> 
                            <div class="table-responsive mt-4">
                                <table style="font-size:90%;" id="datatable" class="table table-hover table-secondary table-striped datatable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:0%;"> # </th>
                                            <th> Item </th>
                                            <th> Categoria </th>
                                            <th style="width:0%;"><nobr> Quantidade Atual </nobr></th>
                                    <th style="width:0%;"><nobr> Adicionar </nobr> </th>
                                    <th style="width:0%;"><nobr> Quantidade futura </nobr> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cont = 1;
                                        $sqlEntrada = "SELECT iditem,
                                                                  item,
                                                                  categoria,
                                                                  quantidade
                                                           FROM estoque
                                                           WHERE ativo = 's'";
                                        $resultEntrada = mysqli_query($con, $sqlEntrada);
                                        while ($rowEntrada = mysqli_fetch_array($resultEntrada)) {
                                            ?>
                                        <input hidden type="number" readonly name="quant-item" value="<?= $cont ?>"> 
                                        <input hidden type="number" readonly name="id[]" value="<?= $rowEntrada[0] ?>"> 
                                        <tr> 
                                            <td style="width:0%;"><?= $rowEntrada[0] ?> </td>
                                            <td><?= $rowEntrada[1] ?> </td>
                                            <td><?= $rowEntrada[2] ?> </td>
                                            <td style="width:0%;"> <input class="form-control form-control-sm" id="quantEntrada_<?= $cont ?>" type="number" readonly name="quantidade[]" value="<?= $rowEntrada[3] ?>"> </td>
                                            <td style="width:0%;"> <input class="form-control form-control-sm adicionar-entrada" id="add_<?= $cont ?>" min='1' type="number" name="adicionar[]" value=''">  </td>
                                            <td style="width:0%;"> <input class="form-control form-control-sm" id="futuraEntrada_<?= $cont ?>" type="number" readonly name="futura[]" value="<?= $rowEntrada[3] ?>"> </td>
                                        </tr>
                                        <?php
                                        $cont++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <button class="btn btn-sm btn-success float-end mt-2" name="salvar"> Adicionar </button>
                        </form>
                    </div>

                    <div class="tab-pane fade <?= $activeSaida ?>" id="saida" role="tabpanel" aria-labelledby="saida-tab">
                        <form action="include/gSaida.php" method="POST"> 
                            <div class="table-responsive mt-4">
                                <table style="font-size:90%;" id="datatable" class="table table-hover table-secondary table-striped datatable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:0%;"> # </th>
                                            <th> Item </th>
                                            <th> Categoria </th>
                                            <th style="width:0%;"><nobr> Quantidade Atual </nobr></th>
                                    <th style="width:0%;"><nobr> Retirar </nobr> </th>
                                    <th style="width:0%;"><nobr> Quantidade futura </nobr> </th>
                                    <th ><nobr> Motivo </nobr> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contSaida = 1;
                                        $sqlSaida = "SELECT iditem,
                                                            item,
                                                            categoria,
                                                            quantidade
                                                    FROM estoque
                                                    WHERE ativo = 's'
                                                    AND quantidade > 0
                                                    ";
                                        $resultSaida = mysqli_query($con, $sqlSaida);
                                        while ($rowSaida = mysqli_fetch_array($resultSaida)) {
                                            ?>
                                        <input hidden type="number" readonly name="id[]" value="<?= $rowSaida[0] ?>"> 
                                        <input hidden type="number" readonly name="quant-item" value="<?= $contSaida ?>"> 
                                        <tr> 
                                            <td style="width:0%;"><?= $rowSaida[0] ?> </td>
                                            <td><?= $rowSaida[1] ?> </td>
                                            <td style="width:0%;"><?= $rowSaida[2] ?> </td>
                                            <td style="width:0%;"> <input class="form-control form-control-sm" id="quantSaida_<?= $contSaida ?>" type="number" readonly name="quantidade[]" value="<?= $rowSaida[3] ?>"> </td>
                                            <td style="width:0%;"> <input class="form-control form-control-sm adicionar-saida" id="rem_<?= $contSaida ?>" min='0' type="number" name="retirar[]" value="">   </td>
                                            <td style="width:0%;"> <input class="form-control form-control-sm" id="futuraSaida_<?= $contSaida ?>" type="number" readonly name="futura[]" value="<?= $rowSaida[3] ?>"> </td>
                                            <td > <input class="form-control form-control-sm" id="motivoSaida<?= $contSaida ?>" type="text" name="motivo[]"> </td>
                                        </tr>
                                        <?php
                                        $contSaida++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <button class="btn btn-sm btn-success float-end mt-2" name="salvar"> Retirar </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--Modais-->
        <!--Adicionar Item ao estoque-->
        <div class = "modal fade" id="modalItem" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog modal-lg">
                <div class = "modal-content cModalItem">
                    <div class="modal-header"> 
                        <h2> Carregando... </h2>
                    </div>
                    <div class="modal-footer"> 

                    </div> 
                </div>
            </div>
        </div>
        <!--Cadastrar categoria-->
        <div class = "modal fade" id = "modalCategoria" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog">
                <div class = "cModalCategoria">
                    Carregando...
                </div>
            </div>
        </div>
        <!--Editar Acomodação-->
        <div class = "modal fade" id = "modalEditarItem" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog">
                <div class = "cModalEditarItem">
                    Carregando...
                </div>
            </div>
        </div>
        <?php
        unset($_SESSION['tipoEstoque']);
        ?>
        <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
        <!--java scripito-->  
        <!--Bootstrap-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script> 
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <!--Mask input-->
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <!--date picker-->
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>

        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="<?= BASED ?>/assets/js/main.js"></script>
        <script src="js/estoque.js"></script>
        <script src="js/entradaSaida.js"></script>
    </body>

</html>