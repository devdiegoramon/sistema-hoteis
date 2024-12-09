<?php
include "../../config/connMysql.php";
include "../../config/config.php";
if (!isset($_POST['id'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    return;
}
$id = $_POST['id'];
$sqlSelectAcomodacao = "SELECT a.idacomodacao,
                               a.nome,
                               a.numero,
                               t.nome,
                               a.valor,
                               a.capacidade,
                               a.descricao,
                               a.ativo,
                               a.idtipoacomodacao,
                               a.cor
                        FROM acomodacao a
                        INNER JOIN tipoacomodacao t on (t.idtipoac = a.idtipoacomodacao) 
                        WHERE a.idacomodacao = {$id}";
$resultSelectAcomodacao = mysqli_query($con, $sqlSelectAcomodacao);
$rowSelectAc = mysqli_fetch_array($resultSelectAcomodacao);
?>

<div class="modal-header">
    <h5 class="modal-title "><span> #<?= $rowSelectAc[0] ?>  </span> Editar acomodação <?= $rowSelectAc[1] ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body col-12">
    <div id="resposta-acomodacao"> </div>
    <form class="row g-2 formulario-acomodacao" id="formulario-edtiarAc" action="include/aAcomodacao.php" method="POST">
        <div class="col-md-6">
            <label class="form-label" for="nome"> Nome <small class='text-danger'> * </small> </label>
            <input type="number" value="<?= $rowSelectAc[0] ?>" name="id" hidden>
            <input class="form-control form-control-sm" required type="text" value="<?= $rowSelectAc[1] ?>" name="nome" placeholder="Ex.: Lopes" title="Nome da acomodação">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="numero"> Número <small class='text-danger'> * </small> </label>
            <input class="form-control form-control-sm" required type="number" value="<?= $rowSelectAc[2] ?>"  name="numero" min="0" placeholder="Ex.: 100" title="Número da acomodação">
        </div>
        <div class="col-md-12">
            <label class="form-label" for="tipo"> Tipo <small class='text-danger'> * </small> </label>
            <select class="form-select form-select-sm" required name="tipo" id="tipo-acomodacao" title="Tipo de acomodação">
                <option value="<?= $rowSelectAc[8] ?>" selected > <?= $rowSelectAc[3] ?>  </option>
                <?php
                $sqlConsultaTipo = "SELECT idtipoac, nome FROM tipoacomodacao WHERE ativo = 's' and idtipoac != {$rowSelectAc[8]}";
                $resultConsultaTipo = mysqli_query($con, $sqlConsultaTipo);
                if (mysqli_num_rows($resultConsultaTipo) > 0) {
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
        <div class="col-md-6">
            <label class="form-label" for="valor"> Valor(diária) </label>
            <div class="input-group input-group-sm mb-2">
                <span class="input-group-text">R$</span>
                <input class="form-control form-control-sm money" type="text" value="<?= $rowSelectAc[4] ?>" name="valor" title="Valor da acomodação" placeholder="Ex.: 1500,00">
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="capacidade">  Capacidade <small class='text-danger'> * </small> </label>
            <div class="input-group input-group-sm mb-2">
                <span class="input-group-text"><i class="fa-solid fa-user-group"></i></span>
                <input class="form-control form-control-sm" required type="number" value="<?= $rowSelectAc[5] ?>" name="capacidade" min="0" title="Capacidade máxima" placeholder="Ex.: 5">
            </div>
        </div>
        <div class="col-md-12 ">
            <label class="form-label" for="descricao">  Descrição </label>
            <textarea data-ls-module="charCounter" maxlength="240" name="descricao" class="form-control" cols="30" rows="3" title="Informe uma descrição" placeholder="Descrição da acomodação"><?= $rowSelectAc[6] ?></textarea>
        </div>
    </form>
    <div class='mt-2'> 
        <small class='text-danger'>* Obrigatório </small>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-sm btn-success" type="submit" name="cadastrar" form="formulario-edtiarAc"><i class="fa-solid fa-pencil"></i> Alterar </button>
</div>

<script src="<?= BASED ?>/acomodacao/js/acomodacao.js"></script>