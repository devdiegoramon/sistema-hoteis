<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/components.php";
include "../include/func.php";

for ($i = 0; $i < 10; $i++) {
    $rowDadosEndereco[$i] = '';
    $rowDadosHospedagem[$i] = '';
}

/* Dados da hospedagem / estabelecimento */
$sqlDadosHospedagem = "select cnpj,
                              razaosocial,
                              website,
                              email,
                              telefone,
                              celular
                       from estabelecimento";
$resultDadosHospedagem = mysqli_query($con, $sqlDadosHospedagem);
if (mysqli_num_rows($resultDadosHospedagem) > 0) {
    $rowDadosHospedagem = mysqli_fetch_array($resultDadosHospedagem);
}

/* Dados do endereço */
$sqlDadosEndereco = "select logradouro,
                            numero,
                            complemento,
                            cidade,
                            bairro,
                            cep
                    from enderecoestabelecimento";
$resultDadosEndereco = mysqli_query($con, $sqlDadosEndereco);
if (mysqli_num_rows($resultDadosEndereco) > 0) {
    $rowDadosEndereco = mysqli_fetch_array($resultDadosEndereco);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $iconeSite ?>
        <link href="<?= BASED ?>/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/dropify/dropify.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
        <link href="<?= BASED ?>/assets/vendor/date-picker/bootstrap-datepicker.min.css" type="text/css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
        <title>Painel de administrador</title>
        <style>
            .dropify-wrapper .dropify-message p{
                font-size: initial;
            }
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
        <div class="row"> 
            <div class="mensagem mt-3"> 
                <?php
                if (isset($_GET['text']) and isset($_GET['type'])) {
                    echo alerta($_GET['text'], $_GET['type']);
                }
                ?>
            </div>
            <div class="col-12 mt-4">
                <button type="button" class="btn btn-sm btn-dark-blue" data-bs-toggle="modal" data-bs-target="#modalLogo"> <i class="fa fa-pen"></i> Alterar Logo </button>
            </div>
            <div class="col-md-6 mb-4"> 
                <div class=" card shadow mt-3">
                    <div class="card-header text-center text-black">
                        <i class="fa-solid fa-address-card"></i>  <span> Dados da Hopedagem </span>
                    </div>
                    <div class="card-body"> 
                        <form class="row g-2" action="include/gDadosHospedagem.php" method="POST" id="form-hospedagem">
                            <div class="col-md-12 mt-1"> 
                                <label class="form-label" for="cnpj"> CNPJ </label>
                                <input maxlength="255" value='<?= $rowDadosHospedagem[0] ?>' autocomplete="off" class="form-control form-control-sm cnpj" type="text" name="cnpj">
                            </div>

                            <div class="col-md-12 mt-2"> 
                                <label class="form-label" for="razaosocial"> Razão social </label>
                                <input maxlength="255" value='<?= $rowDadosHospedagem[1] ?>' autocomplete="off" class="form-control form-control-sm" type="text" name="razaosocial">
                            </div>

                            <div class="col-md-12 mt-2"> 
                                <label class="form-label" for="email"> E-mail </label>
                                <input maxlength="255" value='<?= $rowDadosHospedagem[3] ?>' autocomplete="off" class="form-control form-control-sm" type="email" name="email">
                            </div>  

                            <div class="col-md-12 mt-2"> 
                                <label class="form-label" for="site"> Site </label>
                                <input maxlength="255" value='<?= $rowDadosHospedagem[2] ?>' autocomplete="off" class="form-control form-control-sm" type="text" name="site">
                            </div>

                            <div class="col-md-6 mt-2"> 
                                <label class="form-label" for="telefone"> Telefone </label>
                                <input maxlength="255" value='<?= $rowDadosHospedagem[4] ?>' autocomplete="off" class="form-control form-control-sm phone" type="text" name="telefone">
                            </div>

                            <div class="col-md-6 mt-2"> 
                                <label class="form-label" for="celular"> Celular </label>
                                <input maxlength="255" value='<?= $rowDadosHospedagem[5] ?>' autocomplete="off" class="form-control form-control-sm celular" type="text" name="celular">
                            </div>

                            <div class="col-md-12 mt-2"> 
                                <button class="btn btn-sm btn-primary mt-3 float-end" form='form-hospedagem' type="submit" name="validar"><i class="fa-solid fa-floppy-disk"></i> Gravar </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4"> 
                <div class=" card shadow mt-3">
                    <div class="card-header text-center text-black">
                        <i class="fa-solid fa-location-dot"></i>  <span> Endereço da Hospedagem </span>
                    </div>
                    <div class="card-body"> 
                        <form class="row g-2" action="include/gEndereco.php" method="POST" id='form_endereco'>
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="cidade"> CEP </label> <span id='cep_invalido' class='badge bg-danger'> CEP inválido  </span>
                                <input value='<?= $rowDadosEndereco[5] ?>' maxlength="255" id="cep" autocomplete="off" required class="form-control form-control-sm cep" type="text" name="cep">
                            </div>

                            <div class="col-md-12 mt-1"> 
                                <label class="form-label" for="logradouro"> Logradouro </label>
                                <input value='<?= $rowDadosEndereco[0] ?>' maxlength="255" id="logradouro"  autocomplete="off" required class="form-control form-control-sm" type="text" name="logradouro">
                            </div>

                            <div class="col-md-2 mt-2"> 
                                <label class="form-label" for="dtnasc"> Número </label>
                                <input value='<?= $rowDadosEndereco[1] ?>' maxlength="255" autocomplete="off" class="form-control form-control-sm" type="text" name="numero">
                            </div>

                            <div class="col-md-10 mt-2"> 
                                <label class="form-label" for="complemento"> Complemento </label>
                                <input value='<?= $rowDadosEndereco[2] ?>' maxlength="255" autocomplete="off" class="form-control form-control-sm" type="text" name="complemento">
                            </div>


                            <div class="col-md-12 mt-2"> 
                                <label class="form-label" for="cidade"> Cidade </label>
                                <input value='<?= $rowDadosEndereco[3] ?>' maxlength="255" id='cidade'  autocomplete="off" required class="form-control form-control-sm" type="text" name="cidade">
                            </div>

                            <div class="col-md-12 mt-2"> 
                                <label class="form-label" for="bairro"> Bairro </label>
                                <input value='<?= $rowDadosEndereco[4] ?>' maxlength="255" id='bairro'  required class="form-control form-control-sm" type="text" name="bairro"> 
                            </div>

                            <div class="col-md-12 mt-2"> 
                                <button class="btn btn-sm btn-primary mt-3 float-end" type="submit" name="validar" form='form_endereco' id="btn_endereco"><i class="fa-solid fa-floppy-disk"></i> Gravar </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include "include/mLogo.php";
        ?>

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
        <!--dropify-->
        <script src="<?= BASED ?>/assets/vendor/dropify/dropify.min.js"></script>
        <!--js-->
        <script src="<?= BASED ?>/assets/js/main.js"></script>
        <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
        <script src="js/hospedagem.js"></script>
    </body>

</html>