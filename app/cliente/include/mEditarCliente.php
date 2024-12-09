<?php
include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";
if (!isset($_POST['id'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    return;
}
$idCliente = $_POST['id'];
$sqlSelectCliente = "SELECT idcliente,
                            nome,
                            cpf,
                            dtnasc,
                            email,
                            telefone,
                            estado,
                            cidade,
                            datag
                     FROM cliente WHERE idcliente = {$idCliente}";
$resultSelectCliente = mysqli_query($con, $sqlSelectCliente);
$rowSelectCli = mysqli_fetch_array($resultSelectCliente);
?>
<script>
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.date').mask('00/00/0000');
    $('.phone').mask('(00) 00000-0000');
</script> 
<div class="modal-header">
    <img style='height: 2rem; margin-right: 15px' src="img/cliente.png"> <h5 class="modal-title "> Editar cliente: <span> #<?= $idCliente ?> </span> <span class="border-bottom"><?= $rowSelectCli[1] ?></span></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body col-12">
    <div id="resposta-acomodacao"> </div>
    <form class="row g-2" id="formulario-edtiarCli" action="include/aCliente.php" method="POST">
        <input class="form-control form-control-sm" hidden type="text" name="idcliente" value='<?= $idCliente ?>'>
        <div class="col-md-12">
            <label class="form-label" for="nome"> Nome completo <small class='text-danger'> * </small> </label>
            <input class="form-control form-control-sm" required type="text" name="nome" value='<?= $rowSelectCli[1] ?>' placeholder="Ex.: Finn Cleyde" title="Nome do cliente">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="dtnasc"> Data de Nascimento <small class='text-danger'> * </small> </label>
            <input autocomplete="off" class="form-control form-control-sm date" id="datepicker" required type="text" name="dtnasc" value='<?= dataBrasil($rowSelectCli[3]) ?>' title="Data de Nascimento" placeholder="Ex.: 00/00/0000" title="Data de Nascimento do cliente">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="cpf"> Cpf <small class='text-danger'> * </small> </label>
            <input class="form-control form-control-sm cpf" required type="text" name="cpf" value='<?= $rowSelectCli[2] ?>' placeholder="Ex.: 000-000-000.00" title="Cpf do cliente">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="telefone"> Telefone - Celular <small class='text-danger'> * </small> </label>
            <input class="form-control form-control-sm phone" required type="text" name="telefone" value='<?= $rowSelectCli[5] ?>' placeholder="Ex.: (00) 0000-00000" title="Telefone do cliente">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="email"> Email <small class='text-danger'> * </small> </label>
            <input class="form-control form-control-sm" required type="email" name="email" value='<?= $rowSelectCli[4] ?>' placeholder="Ex.: finnCleyde@email.com" title="Email do cliente">
        </div>
        <div class="col-md-6 ">
            <label class="form-label" for="estado"> Estado <small class='text-danger'> * </small> </label>
            <select  class="form-select form-select-sm" required name="estado" id="estado-editar" title="Estado onde cliente reside"> 

            </select>
        </div>
        <div class="col-md-6 ">
            <label class="form-label" for="Cidade"> Cidade <small class='text-danger'> * </small> </label>
            <select  class="form-select form-select-sm" required name="cidade" id="cidade-editar" value='<?= $rowSelectCli[7] ?>' placeholder="Selecione um estado" title="Cidade onde cliente reside"> 
            </select>
        </div>
    </form>
    <div class='mt-2'> 
        <small class='text-danger'>* Obrigatório </small>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-sm btn-success" type="submit" name="editar" form="formulario-edtiarCli"> <i class="fa-solid fa-pencil"></i> Alterar </button>
</div>

<script>
    //POPULANDO SELECT (EDITAR CLIENTE)

    //Links APIS
    var apiurl = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome';
    var nomeurl = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/<?= $rowSelectCli[6] ?>';

    //SELECIONA O ESTADO CADASTRADO 
    var estado = '';
    $.getJSON(nomeurl, function (data) {
        estado += '<option value="' + data.sigla + '" selected>' + data.nome + '</option>';
    }).done(function () {
        //ADICIONA OS PROXIMOS ESTADOS
        $.getJSON(apiurl, function (data) {
            $.each(data, function (v, val) {
                let sigla = val.sigla;
                if (sigla !== '<?= $rowSelectCli[6] ?>') {
                    estado += '<option value="' + val.sigla + '">' + val.nome + '</option>';
                }
            });
            $("#estado-editar").html(estado);
        });
    });

    //SELECIONA PRIMEIRO A CIDADE CADASTRADA E DEPOIS AS DEMAIS DISPONIVEIS
    var cidadeCliente = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/<?= $rowSelectCli[6] ?>/municipios';
    var cidade = '<option value="<?= $rowSelectCli[7] ?>"><?= $rowSelectCli[7] ?></option>';
    $.getJSON(cidadeCliente, function (data) {
        $.each(data, function (v, val) {
            let nomeCidade = val.nome;
            if (nomeCidade !== '<?= $rowSelectCli[7] ?>')
                cidade += '<option value="' + val.nome + '">' + val.nome + '</option>';
        });
        $("#cidade-editar").html(cidade);
    });

    //SELECIONA AS DEMAIS CIDADES, CONFORME OS ESTADOS
    $("#estado-editar").on('change', function (e) {
        let uf = ($(this).val());
        let urlCidade = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + uf + '/municipios';
        $.getJSON(urlCidade, function (data) {
            let cidade = '';
            $.each(data, function (v, val) {
                cidade += '<option value="' + val.nome + '">' + val.nome + '</option>';
            });
            $("#cidade-editar").html(cidade);
        });
    });


</script>
