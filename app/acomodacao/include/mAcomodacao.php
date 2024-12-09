<?php
include "../../config/connMysql.php";
include "../../config/config.php";
if (!isset($_GET['validar'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    return;
}
?>
<style>
    .campo i {
        font-weight: bolder;
        font-size: 75%;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">Cadastrar nova Acomodação</h5>
    <smallutton type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body col-12">
    <div id="resposta-acomodacao"> </div>
    <form  enctype='multipart/form-data' class="row g-2 formulario-acomodacao" id="formulario-acomodacao" action="include/gAcomodacao.php" method="POST">
        <div class="col-md-6">
            <label class="form-label" for="nome"> Nome <small class='text-danger'> * </small> </label>
            <input class="form-control form-control-sm" required type="text" name="nome" id="nome-acomodacao" placeholder="Nome da acomodação" title="Nome da acomodação">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="numero"> Número <small class='text-danger'> * </small> </label>
            <input class="form-control form-control-sm" required type="number" name="numero" min="0" id="numero-acomodacao" placeholder="Número da acomodação" title="Número da acomodação">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="tipo"> Tipo <small class='text-danger'> * </small> </label>
            <select class="form-select form-select-sm" required name="tipo" id="tipo-acomodacao" title="Tipo de acomodação">
                <?php
                $sqlConsultaTipo = "SELECT idtipoac, nome FROM tipoacomodacao WHERE ativo = 's'";
                $resultConsultaTipo = mysqli_query($con, $sqlConsultaTipo);
                if (mysqli_num_rows($resultConsultaTipo) > 0) {
                    echo '<option selected value="" class="form-option">Selecione uma opção</option>';
                    while ($rowTipo = mysqli_fetch_array($resultConsultaTipo)) {
                        echo '<option value="' . $rowTipo[0] . '" class="form-option">' . $rowTipo[1] . '</option>';
                    }
                } else {
                    echo '<option value="" class="form-option">Nenhuma opção cadastrada</option>';
                }
                ?>
            </select>
        </div>
        <div class='col-md-6 col-sm-12' style="margin-top:14px;">
            <?php
            $sqlVaga = "SELECT idvaga,
                                numero
                         FROM estacionamento 
                         WHERE ativo = 's'
                         AND idacomodacao IS NULL";
            $resultVaga = mysqli_query($con, $sqlVaga);
            if (mysqli_num_rows($resultVaga) > 0) {
                $disabled = '';
            } else {
                $disabled = 'disabled';
            }
            ?>
            <div class="form-check form-switch col-12">
                <input <?= $disabled ?> onchange="verificaCheck()" class="form-check-input" type="checkbox" id="checkadd">
                <label class="form-check-label" for="checkadd"> Vaga no estacionamento </label>
            </div>
            <select multiple disabled class="form-select form-select-sm select2" name="vaga[]" id='vaga'> 
                <?php
                if (mysqli_num_rows($resultVaga) > 0) {
                    while ($rowVaga = mysqli_fetch_array($resultVaga)) {
                        ?>
                        <option value="<?= $rowVaga[0] ?>-<?= $rowVaga[1] ?>">Nº <?= $rowVaga[1] ?></option>;
                        <?php
                    }
                } else {
                    echo "<option disabled selected> Sem vagas disponíveis </option>";
                }
                ?>
            </select>

        </div>
        <div class="col-md-6 mt-3">
            <label class="form-label" for="valor"> Valor(diária)</label>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text">R$</span>
                <input class="form-control form-control-sm money" type="text" name="valor" id="valor-acomodacao" title="Valor da acomodação" placeholder="Ex.: 1500,00">
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <label class="form-label" for="capacidade"> Capacidade <small class='text-danger'> * </small> </label>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text"><i class="fa-solid fa-user-group"></i></span>
                <input class="form-control form-control-sm" required type="number" name="capacidade" min="0" id="capacidade-acomodacao" title="Capacidade máxima" placeholder="Ex.: 5">
            </div>
        </div>
        <div class="col-md-12 ">
            <label class="form-label" for="descricao"> Descrição </label>
            <textarea  data-ls-module="charCounter" maxlength="150" name="descricao" class="form-control" cols="30" rows="3" id="descricao-acomodacao" title="Informe uma descrição" placeholder="Descrição da acomodação"></textarea>
        </div>
    </form>
    <div class='mt-2'> 
        <small class='text-danger'> * Obrigatório </small>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-sm btn-success" type="submit" name="cadastrar" form="formulario-acomodacao"><i class="fa-regular fa-floppy-disk"></i> Gravar </button>
</div>
<script>
    $('.money').mask('0.000,00', {reverse: true});
    function verificaCheck() {
        let check = $('#checkadd');
        if (check.is(':checked')) {
            $('#vaga').prop("disabled", false);
        } else {
            $('#vaga').prop("disabled", true);
            $('#vaga').val('');
        }
    }
</script> 