<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/components.php";
include "../include/func.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $iconeSite ?>
        <link href="<?= BASED ?>/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Painel de administrador</title>
        <style>
            .alert {
            }

            .btn-status{
                cursor:pointer;
            }
            .grafico{
                border-radius: 0.3rem;
            }

            #valor-grafico{
                font-weight: bolder;
                font-family: sans-serif;
                color:#212F3D;
            }
            .table-vendas {
                font-size: 80%;
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
        </style>
    </head>

    <body id="body-pd" class="body-pd">
        <?php include "../include/sidebar.php" ?>
        <div class="d-flex justify-content-end"> 
            <a onclick="modalHistorico()" class="btn btn-secondary btn-sm mt-4"> <i class="fa-solid fa-book"></i> Histórico de alterações </a>
        </div>
        <div class="mensagem mt-3"> 
            <?php
            if (isset($_GET['text']) and isset($_GET['type'])) {
                echo alerta($_GET['text'], $_GET['type']);
            }
            ?>
        </div>
        <div class="row"> 
            <div class="col-md-4"> 
                <div class=" card shadow mt-3">
                    <div class="card-header text-black">
                      <i class="fa-solid fa-address-card"></i>  <span> Cadastro de funcionário </span>
                    </div>
                    <div class="card-body"> 

                        <form class="row g-2" action="include/gFuncionario.php" method="POST">
                            <div class="col-md-12 mt-1"> 
                                <label class="form-label" for="nome"> Nome completo </label>
                                <input autocomplete="off" required class="form-control form-control-sm" type="text" name="nome">
                            </div>

                            <div class="col-md-6 mt-1"> 
                                <label class="form-label" for="dtnasc"> Data de nascimento </label>
                                <input autocomplete="off" required class="form-control form-control-sm date datepicker" type="text" name="dtnasc">
                            </div>

                            <div class="col-md-6 mt-1"> 
                                <label class="form-label" for="cpf"> CPF </label>
                                <input autocomplete="off" required class="form-control form-control-sm cpf" type="text" name="cpf">
                            </div>

                            <div class="col-md-12 mt-1"> 
                                <label class="form-label" for="nivel"> Função </label>
                                <select required class="form-select form-select-sm" name="nivel">
                                    <option value="2"> Funcionário </option>
                                    <option value="1"> Administrador </option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-1"> 
                                <label class="form-label" for="login"> Login </label>
                                <input autocomplete="off" required class="form-control form-control-sm" type="text" name="login">
                            </div>

                            <div class="col-md-6 mt-1"> 
                                <label class="form-label" for="senha"> Senha </label>
                                <input required class="form-control form-control-sm" type="password" name="senha"> 
                            </div>

                            <div class="col-md-12 mt-1"> 
                                <button class="btn btn-sm btn-primary mt-3 float-end" type="submit" name="btn-cadastro" id="btn-login"><i class="fa-solid fa-floppy-disk"></i> Cadastrar </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8"> 
                <div class=" card shadow mt-3">
                    <div class="card-header text-black">
                        <i class="fa-solid fa-id-card-clip"></i> <span> Funcionarios cadastrados</span>
                    </div>
                    <div class="card-body"> 
                        <div class="table-responsive"> 
                            <table style="font-size:90%;" class="table table-secondary table-hover table-striped" id="datatable"> 
                                <thead> 
                                    <tr> 
                                        <th class="width-0"> ID </th>
                                        <th> Nome </th>
                                        <th class="width-0"> CPF </th>
                                        <th class="width-0"> Tipo </th>
                                        <th class="width-0"> Status </th>
                                        <th class="width-0"> Ação </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-funcionario"> 

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Editar Funcionario-->
        <div class = "modal fade" id = "modalEditarFuncionario" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog">
                <div class = "modal-content cModalEditarFuncionario">
                    <div class="modal-header"> 
                        <h2> Carregando... </h2>
                    </div>
                    <div class="modal-footer"> 

                    </div> 
                </div>
            </div>
        </div>

        <!--Historico -->
        <div class = "modal fade" id = "modalHistorico" tabindex = "-1" aria-labelledby = "" aria-hidden = "true">
            <div class = "modal-dialog modal-xl">
                <div class = "modal-content cModalHistorico">
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
        <!--datepicker-->
        <script src="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/date-picker/datepicker.pt-BR.min.js"></script>
        <!--data table-->
        <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
        <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
        <!--js-->
        <script src="<?= BASED ?>/assets/js/main.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="js/admin.js"></script>
    </body>

</html>