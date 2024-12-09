<?php
include "../../config/connMysql.php";
include "../../config/config.php";
?>
<div class="modal-header">
    <h5 class="modal-title">Cadastrar frigobar</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body col-12">
    <div id="resposta-acomodacao"> </div>
    <form class="row g-2 formulario-acomodacao" id="formulario-frigobar" action="include/gFrigobar.php" method="POST">
        <div class="col-md-6">
            <small class="form-label" for="modelo"> <b> Modelo </b> <b class='text-danger'> * </b> </small>
            <input class="form-control form-control-sm" required type="text" name="modelo" title="Modelo do frigobar">
        </div>
        <div class="col-md-6">
            <small class="form-label" for="patrimonio"> <b> Patrimônio </b> <b class='text-danger'> * </b> </small>
            <input class="form-control form-control-sm" required type="text" name="patrimonio" min="0" title="Patrimônio do frigobar">
        </div>
        <div class="col-md-12">
            <small class="form-label" for="acomodacao"><b>  Acomodação  </b> <b class='text-danger'> * </b> </small>
            <select class="form-select form-select-sm select2" required name="acomadacao" id="acomodacao" title="Acomodação do frigobar">
                <?php
                $sqlConsultaTipo = "SELECT idacomodacao, nome FROM acomodacao WHERE ativo = 's'";
                $resultConsultaTipo = mysqli_query($con, $sqlConsultaTipo);
                if (mysqli_num_rows($resultConsultaTipo) > 0) {
                    echo '<option selected value="" class="form-option">Selecione uma opção</option>';
                    while ($rowTipo = mysqli_fetch_array($resultConsultaTipo)) {
                        echo '<option value="' . $rowTipo[0] . '" class="form-option">' . $rowTipo[1] . '</option>';
                    }
                } else {
                    echo '<option value="" class="form-option">Nenhuma opção cadastrada</option>';
                }
                mysqli_close($con);
                ?>
            </select>
        </div>
    </form>
    <div class='mt-2'> 
        <small class='text-danger'>* Obrigatório </small>
    </div>

</div>
<div class="modal-footer">
    <button class="btn btn-sm btn-success" type="submit" name="cadastrar" form="formulario-frigobar"> <i class="fa-regular fa-floppy-disk"></i> Gravar </button>
</div>

<script src="<?= BASED ?>/frigobar/js/frigobar.js"></script>