<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";
include "include/cDadosReserva.php";
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
        <link href="<?= BASED ?>/assets/vendor/select2/select2.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/select2/select2-bootstrap-5-theme.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Reserva</title>
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
            .my-shadow{
                background-color: white;
                -webkit-box-shadow: 5px -9px 38px 15px rgba(0,0,0,0.05);
                box-shadow: 5px -9px 38px 15px rgba(0,0,0,0.05);
            }
            .my-badge {
                margin-right: 10px;
                margin-top: 10px;
            }

            .modal-open {
                padding-right: 16px !important;
            }

            body {
                overflow-y: scroll !important;
            }

            .group{
                background-color:#5D6D7E;
                color:white;
            }
        </style>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <a class="btn btn-sm btn-dark-blue mt-4" href="index.php"> <i class="fa-regular fa-circle-left"></i> Reservas </a>
        <div data-bs-toggle="collapse" class="checkin-fake mt-3"> 
            <?php
            echo alerta("Não é possível realizar check-in antes da data de entrada", 2);
            ?>
        </div>
        <div class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header"> 
                <h5 >Dados da reserva Nº <b> <?= $_GET['id'] ?> </b></h5>
            </div>
            <div class="card-body">
                <?php include "include/header.php" ?>
                <!--DADOS DA RESERVA-->
                <form class="row col-md-12"> 
                    <h5> Reserva </h5>
                    <div class="col-md-6 mt-2"> 
                        <label for="nome-cliente" class="form-label"> Cliente </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                            <input name="nome-cliente" type="text" value="<?= $rowReserva['nome'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-6 mt-2"> 
                        <label for="cpf" class="form-label"> CPF </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><i class="fa-solid fa-id-card"></i></span>
                            <input name="cpf" type="text" value=" <?= $rowReserva['cpf'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>

                    <!--ACOMODACAO-->
                    <div class="col-md-6 mt-2"> 
                        <label for="nome-acomodacao" class="form-label"> Acomodação </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><i class="fa-solid fa-house"></i></span>
                            <input name="nome-acomodacao" type="text" value="<?= $rowReserva[14] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-3 mt-2"> 
                        <label for="numero-acomodacao" class="form-label"> Número </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><i class="fa-solid fa-hashtag"></i></span>
                            <input name="numero-acomodacao" type="number"value="<?= $rowReserva['numero'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-3 mt-2"> 
                        <label for="diaria" class="form-label"> Valor da Diária </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><strong> R$ </strong></span>
                            <input name="diaria" type="text" value="<?= converteReal($rowReserva[23]) ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>

                    <div class="col-md-4 mt-2"> 
                        <label for="entrada-pre" class="form-label"> Entrada prevista </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><i class="fa-solid fa-calendar-check"></i></span>
                            <input name="entrada-pre" type="text" value="<?= dataBrasil($rowReserva[4]) ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>
                    <div class="col-md-4 mt-2"> 
                        <label for="saida-pre" class="form-label"> Saida prevista </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><i class="fa-solid fa-calendar-xmark"></i></span>
                            <input name="saida-pre" type="text" value="<?= dataBrasil($rowReserva[5]) ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>

                    <div class="col-md-4 mt-2"> 
                        <label for="hospedes" class="form-label">Quantidade de hospedes </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><i class="fa-solid fa-users"></i></span>
                            <input name="hospedes" type="text" value="<?= $rowReserva['quantidadehospedes'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>

                    <div class="col-12 mt-2"> 
                        <label for="hospedes" class="form-label">Observação </label>
                        <div class="input-group input-group-sm"> 
                            <span class="input-group-text group" id="basic-addon1"><i class="fa-solid fa-comment "></i></span>
                            <input value="<?= $rowReserva['obs'] ?>" disabled class="form-control form-control-sm" > 
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!--CONSUMO-->
    <!--ITENS CONSUMIDOS-->
    <div <?= $hidden ?> class="card shadow mt-3 mb-4">
        <div class="card-header">
            <div class="col-12 d-flex justify-content-md-between"> 
                <h5 >Consumo </b></h5>
                <span> Total Consumido <b>R$ <?= converteReal($rowReserva['valoritens']) ?> </b> </span> 
            </div>
        </div>
        <div class="card-body "> 
            <div class="row d-flex justify-content-between"> 
                <div class="col-lg-6 col-md-12"> 
                    <span class="mb-2"> <i class="fa-solid fa-cart-shopping"></i> Itens consumidos </span>
                    <?php
                    //IMPEDE DE CADASTRAR NOVO ITEM QUANDO A RESERVA FOI CANCELADA
                    if ($rowReserva[12] != 'c' and $rowReserva['datacheckout'] == null and $rowReserva['datacheckin'] != null) {
                        ?>
                        <form class="row mb-3 mt-3" id="form-consumo" method="POST" action="include/gItemConsumido.php?idreserva=<?= $idreserva ?>&idconsumo=<?= $rowReserva['idconsumo'] ?>"> 
                            <div class="col-lg-3 col-md-6 col-sm-12"> 
                                <label class="form-label" for="frig"> 
                                    Frigobar
                                </label>
                                <select onchange="itensFrigobar()" required id="select-frig" name="frig" class="form-select form-select-sm">
                                    <?php
                                    if ($totalFrigobar > 0) {
                                        echo '<option selected value="" class="form-option">Selecione uma opção</option>';
                                        while ($rowFrigobar = mysqli_fetch_array($resultFrigobar)) {
                                            echo '<option value="' . $rowFrigobar[0] . '" class="form-option">' . $rowFrigobar[2] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="" class="form-option">Nenhum frigobar disponível</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12"> 
                                <label class="form-label" for="item-frig"> 
                                    Item
                                </label>
                                <select name="item-frig" onchange="dadosItemFrig()" required id="select-item-frig" class="form-select form-select-sm">
                                    <option selected> Selecione um frigobar </option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <nobr><label class="form-label" id="label-quantidade" for="quantidade-frig"> 
                                        Quantidade
                                    </label> </nobr>
                                <input required disabled class="form-control form-control-sm" type="number" name="quantidade-frig" min="0" id="quantidade-frig">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label mt-3" for="valor-itemFrig"> 
                                </label>
                                <div class="input-group"> 
                                    <input readonly class="form-control form-control-sm" type="text" value="R$ 0,00" name="valor-itemFrig" id="valorItemFrig">
                                    <button class="btn btn-sm btn-success" form="form-consumo" name="acrescentar"> Adicionar </button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>

                    <div class="table-responsive"> 
                        <table style="font-size:80%;" class="table table-secondary table-hover table-striped mt-3"> 
                            <thead> 
                                <tr> 
                                    <th> # </th>
                                    <th> Item </th>
                                    <th> Quantidade </th>
                                    <th> Data </th>
                                    <th> Valor total </th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php
                                $sqlItensConsumidos = "SELECT iditemconsumido,
                                                                   nome,
                                                                   quantidade,
                                                                   valortotal,
                                                                   datag,
                                                                   horag
                                                            FROM itensconsumidos 
                                                            WHERE idconsumo = {$rowReserva['idconsumo']}
                                                            ORDER BY iditemconsumido DESC    
                                                            ";
                                $resultItensConsumidos = mysqli_query($con, $sqlItensConsumidos);
                                while ($rowItens = mysqli_fetch_array($resultItensConsumidos)) {
                                    ?>
                                    <tr> 
                                        <td> <?= $rowItens[0] ?> </td>
                                        <td> <?= $rowItens[1] ?> </td>
                                        <td> <?= $rowItens[2] ?> </td>
                                        <td> <?= dataBrasil($rowItens[4]) ?> - <?= $rowItens[5] ?> </td>
                                        <td> R$ <?= converteReal($rowItens[3]) ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <span class = "mb-2"> <i class = "fa-solid fa-cart-plus"></i> Consumo adicional </span>
                    <?php
                    //IMPEDE DE CADASTRAR NOVO ITEM QUANDO A RESERVA FOI CANCELADA
                    if ($rowReserva[12] != 'c' and $rowReserva['datacheckout'] == null and $rowReserva['datacheckin'] != null) {
                        ?>
                        <form class="row mb-3 mt-3" method="POST" action="include/gConsumoAdicional.php?idconsumo=<?= $rowReserva['idconsumo'] ?>&idreserva=<?= $idreserva ?>"> 
                            <div class="col-md-6"> 
                                <label class="form-label"> Descrição </label>
                                <input name="descricao" class="form-control form-control-sm" required type="text">
                            </div>
                            <div class="col-md-6"> 
                                <label class="form-label"> Valor </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">R$</span>
                                    <input name="valor" class="form-control form-control-sm money" required type="text">
                                    <button class="btn btn-sm btn-success" name="adicionar"> Adicionar </button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                    <div class="table-responsive"> 
                        <table style="font-size:80%;" class="table table-secondary table-hover table-striped mt-3"> 
                            <thead> 
                                <tr> 
                                    <th> # </th>
                                    <th> Motivo </th>
                                    <th> Valor total </th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php
                                $sqlAdicional = "SELECT idadicional,
                                                            motivo,
                                                            valor
                                                    FROM adicionalconsumo 
                                                    WHERE idconsumo = {$rowReserva['idconsumo']}
                                                    ORDER BY idadicional DESC";
                                $resultAdicional = mysqli_query($con, $sqlAdicional);
                                while ($rowAdicional = mysqli_fetch_array($resultAdicional)) {
                                    ?>
                                    <tr> 
                                        <td> <?= $rowAdicional[0] ?> </td>
                                        <td> <?= $rowAdicional[1] ?> </td>
                                        <td> R$ <?= converteReal($rowAdicional[2]) ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Modais-->
    <!--Check-in-->
    <div class = "modal fade" id="modalCheckin" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
        <div class = "modal-dialog">
            <div class = "modal-contetn" id="cModalCheckin">
                <div class="modal-header"> 
                    <h2> Carregando... </h2>
                </div>
                <div class="modal-footer"> 

                </div> 
            </div>
        </div>
    </div>

    <!--Dados do chekin-->
    <div class = "modal fade" id="modalDadosCheckin" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
        <div class = "modal-dialog">
            <div class = "modal-content" id="cModalDadosCheckin">
                <div class="modal-header"> 
                    <h2> Carregando... </h2>
                </div>
                <div class="modal-footer"> 

                </div> 
            </div>
        </div>
    </div>

    <!--Modal checkout-->
    <div class = "modal fade" id="modalCheckout" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
        <div class = "modal-dialog">
            <div class = "modal-content" id="cModalCheckout">
                <div class="modal-header"> 
                    <h2> Carregando... </h2>
                </div>
                <div class="modal-footer"> 

                </div> 
            </div>
        </div>
    </div>
    <!--Dados do checkout-->
    <div class = "modal fade" id="modalDadosCheckout" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
        <div class = "modal-dialog">
            <div class = "modal-content" id="cModalDadosCheckout">
                <div class="modal-header"> 
                    <h2> Carregando... </h2>
                </div>
                <div class="modal-footer"> 

                </div> 
            </div>
        </div>
    </div>

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
    <!--datepicker-->
    <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
    <script src="<?= BASED ?>/assets/vendor/date-picker/datepicker.pt-BR.min.js"></script>
    <!--js-->
    <!--<script src="<?= BASED ?>/assets/js/main.js"></script>-->
    <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
    <script src="js/reserva.js"></script>
</body>
</html>

<!--    
    Script criado e desenvolvido por: Herrison Trugilho 
    Email para contato: herrison.sistemas@gmail.com
-->