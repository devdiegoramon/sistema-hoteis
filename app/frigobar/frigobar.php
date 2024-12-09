<!DOCTYPE html>
<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";
if ((!isset($_GET['idfrigobar'])) or (empty($_GET['idfrigobar']))) {
    header('Location: ../../index.php?text=Sem Acesso&type=1');
}
$idfrigobar = $_GET['idfrigobar'];
$sql = "SELECT f.modelo,
               f.patrimonio,
               f.ativo,
               a.nome,
               a.numero
        FROM frigobar f
        INNER JOIN acomodacao a ON (f.idacomodacao = a.idacomodacao)
        WHERE f.idfrigobar = {$idfrigobar}";
$resultSql = mysqli_query($con, $sql);
$row = mysqli_fetch_array($resultSql);
$sqlItensFrig = "SELECT i.iditemfrigobar,
                        i.iditem,
                        i.quantidade,
                        e.item,
                        e.categoria,
                        e.valorunitario,
                        i.ativo
                FROM itemfrigobar i
                INNER JOIN estoque e ON (i.iditem = e.iditem)
                WHERE i.idfrigobar = {$idfrigobar}
                AND i.quantidade > 0";
$resultSqlItens = mysqli_query($con, $sqlItensFrig);
?>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--CSS -->
        <link href="<?= BASED ?>/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/bootstrap-colorpicker/bootstrap-colorpicker.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Frigobar</title>
        <style>
            input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;

            }
            input[type=number] {
                -moz-appearance: textfield;
                appearance: textfield;

            }
            .btn-icons{
                padding: 10px;
                color: black;
            }
            a {
                text-decoration: none;
                color: black;
            }
            a:hover{
                color:black;
            }

        </style>
    </head>
    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <a href='index.php' class="btn btn-sm btn-dark-blue mt-4"> <i class="fa-regular fa-circle-left"></i> Voltar </a>
        <div class='row'>
            <div class='col-md-4 col-sm-12'>
                <div class="card shadow mt-3">
                    <div class="card-header">
                        <h5>Dados do frigobar Nº<?= $idfrigobar ?> </h5>
                    </div>
                    <div class='card-body'>
                        <form>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label"> Modelo </label>
                                    <input class="form-control form-control-sm" disabled value="<?= $row[0] ?>" type="text">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label"> Patrimônio </label>
                                    <input class="form-control form-control-sm" disabled value="<?= $row[1] ?>" type="text">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"> Acomodação </label>
                                    <input class="form-control form-control-sm" disabled value="<?= $row[3] ?>" type="text">
                                </div>
                            </div>
                            <div class="row mt-3 mb-2">
                                <div class="col-md-6">
                                    <?php
                                    $sqlTotal = "SELECT quantidade FROM itemfrigobar WHERE idfrigobar = {$idfrigobar}";
                                    $resultTotal = mysqli_query($con, $sqlTotal);
                                    $totalItens = 0;
                                    while ($rowTotal = mysqli_fetch_array($resultTotal)) {
                                        $totalItens = $totalItens + $rowTotal[0];
                                    }
                                    ?>
                                    <label class="form-label"> Total de itens: <?= $totalItens ?> </label>
                                    <!--<input class="form-control form-control-sm" disabled value="" type="text">-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class='col-md-8 col-sm-12'> 
                <div class="card shadow mt-3">
                    <div class="card-header"> 
                        <h5>Itens disponíveis </h5>
                    </div>
                    <div class='card-body'> 
                        <a class="btn btn-sm btn-dark-blue" onclick="adicionarItem(<?= $idfrigobar ?>)"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Adicionar Item </a>
                        <div id="resposta-item">...</div>
                        <div class="table-responsive mt-4" > 
                            <table class="table table-striped table-hover table-secondary" style="width:100%;"> 
                                <thead> 
                                    <tr>
                                        <th> # </th>
                                        <th> Item </th>
                                        <th> Categoria </th>
                                        <th> Quantidade </th>
                                        <th> Valor unitario </th>
                                        <th> Status </th>
                                        <th> Ação </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-itemFrig"> 
                                    <?php
                                    while ($rowSqlItens = mysqli_fetch_array($resultSqlItens)) {
                                        if ($rowSqlItens[6] == 's') {
                                            $btn = $btnAtivo;
                                        } else {
                                            $btn = $btnInativo;
                                        }
                                        ?>
                                        <tr> 
                                            <td> <?= $rowSqlItens[0] ?> </td>
                                            <td> <?= $rowSqlItens[3] ?> </td>
                                            <td> <?= $rowSqlItens[4] ?> </td>
                                            <td> <?= $rowSqlItens[2] ?> </td>
                                            <td> R$ <?= converteReal($rowSqlItens[5]) ?> </td>
                                            <td class="text-center"> 
                                                <?php if ($_SESSION['nivel'] == 1) { ?>
                                                    <a onclick="statusItemFrigobar(<?= "$idfrigobar ,'$rowSqlItens[6]', '$rowSqlItens[3]', '$row[0]', '$rowSqlItens[0]'" ?>)" style="cursor:pointer;"><?= $btn ?></a> 
                                                    <?php
                                                } else {
                                                    echo "<a>$btn</a";
                                                }
                                                ?>
                                            </td>
                                            <td class = "text-center">
                                                <a onclick = "devolverItem(<?php echo "$rowSqlItens[0], $idfrigobar, '$row[0]'" ?>)" class = "badge bg-secondary btn-dark" style = "cursor:pointer;" data-html = "true" data-toggle = "tooltip" data-placement = "top" title = "Devolver ao estoque"><i class = "fa fa-reply" aria-hidden = "true"></i></a>
                                            </td>
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
        </div>
        <!--Modais-->
        <!--Adicionar Frigobar-->
        <div class="modal fade" id="modalFrigobarAdicionarItem" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id='cModalAdicionarItem'>
                    <div class="modal-header"> 
                        <h2> Carregando... </h2>
                    </div>
                    <div class="modal-footer"> 

                    </div> 
                </div>
            </div>
        </div>
        <!--java scripito-->  
        <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
        <!--Bootstrap-->
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.min.js"></script> 
        <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/bootstrap-colorpicker/bootstrap-colorpicker.js"></script> 
        <!--Mask input-->
        <script src="<?= BASED ?>/assets/vendor/jquery.mask/jquery.mask.min.js"></script>
        <!--font awesome-->
        <script src="<?= BASED ?>/assets/vendor/fontawesome-6.0.0/js/kit.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="js/frigobar.js"></script>
    </body>

</html>
