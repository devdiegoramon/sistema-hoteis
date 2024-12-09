<?php
include "../../config/connMysql.php";
include "../../config/config.php";

$idFrigobar = $_POST['id'];
$sqlSelectFrigobar = "SELECT f.idacomodacao,
                             f.modelo,
                             f.patrimonio,
                             a.nome
                     FROM frigobar f
                     INNER JOIN acomodacao a on (a.idacomodacao = f.idacomodacao) 
                     WHERE idfrigobar = {$idFrigobar}";
$resultSelectFrigobar = mysqli_query($con, $sqlSelectFrigobar);
$rowSelectFrig = mysqli_fetch_array($resultSelectFrigobar);
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title "><span> #<?= $idFrigobar ?>  </span> <?= $rowSelectFrig[2] ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body col-12">
            <div id="resposta-frigobar"> </div>
            <form class="row g-2 formulario-acomodacao" id="formulario-edtiarFrigobar" action="include/aFrigobar.php" method="POST">
                <div class="col-md-6">
                    <label class="form-label" for="modelo"> Modelo </label>
                    <input required value="<?= $idFrigobar ?>" hidden="" name="idfrigobar">
                    <input class="form-control form-control-sm" required type="text" name="modelo" value="<?= $rowSelectFrig[1] ?>" title="Modelo do frigobar">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="patrimonio"> Patrimônio </label>
                    <input class="form-control form-control-sm" type="text" name="patrimonio" min="0" value="<?= $rowSelectFrig[2] ?>" title="Patrimônio do frigobar">
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="acomodacao"> Acomodação </label>
                    <select class="form-select form-select-sm" required name="acomodacao" id="acomodacao" title="Acomodação do frigobar">
                        <option value="<?= $rowSelectFrig[0] ?>"> <?= $rowSelectFrig[3] ?> </option>
                        <?php
                        $sqlConsultaAcomodacao = "SELECT idacomodacao, nome FROM acomodacao WHERE ativo = 's' AND idacomodacao != {$rowSelectFrig[0]}";
                        $resultConsultaAcomodacao = mysqli_query($con, $sqlConsultaAcomodacao);
                        while ($rowAc = mysqli_fetch_array($resultConsultaAcomodacao )) {
                            echo '<option value="' . $rowAc[0] . '" class="form-option">' . $rowAc[1] . '</option>';
                        }
                        mysqli_close($con);
                        ?>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-sm btn-success" type="submit" name="editar" form="formulario-edtiarFrigobar"> Cadastrar </button>
        </div>
    </div>
</div>

<script src="<?= BASED ?>/frigobar/js/frigobar.js"></script>