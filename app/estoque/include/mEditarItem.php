<?php
include "../../config/connMysql.php";
include "../../config/config.php";
if (!isset($_POST['id'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    return;
}
$id = $_POST['id'];
$sqlSelectItem = "SELECT item,
                         categoria,
                         quantidade,
                         valorunitario
                 FROM estoque WHERE iditem = {$id}";
$resultSelectItem = mysqli_query($con, $sqlSelectItem);
$rowSelectItem = mysqli_fetch_array($resultSelectItem);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Adicionar novo item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body col-12">
            <div id="resposta-acomodacao"> </div>
            <form class="row g-2 formulario-item" id="formulario-item" action="include/aItem.php" method="POST">
                <div class="col-md-6">
                    <label class="form-label" for="categoria"> Categoria </label>
                    <select class="form-select form-select-sm" required name="categoria" title="Categoria do item">
                        <option value="<?= $rowSelectItem[1] ?>" selected> <?= $rowSelectItem[1] ?> </option> 
                        <?php
                        $sqlConsultaCat = "SELECT nome FROM categoriaestoque WHERE ativo = 's' AND nome != '$rowSelectItem[1]'";
                        $resultConsultaCat = mysqli_query($con, $sqlConsultaCat);
                            while ($rowCat = mysqli_fetch_array($resultConsultaCat)) {
                                echo '<option value="' . $rowCat[0] . '" class="form-option">' . $rowCat[0] . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="nome"> Nome </label>
                    <input class="form-control form-control-sm" required type="text" value="<?= $rowSelectItem[0] ?>" name="nome"  placeholder="Ex.: Heineken 200ml" title="Nome do item">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="quantidade"> Quantidade em estoque </label>
                    <input class="form-control form-control-sm" required type="number" value="<?= $rowSelectItem[2] ?>" name="quantidade" min="1" placeholder="Ex.: 100" title="Adicionar quantidade">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="valor"> Valor </label>
                    <input class="form-control form-control-sm money" required type="text" value="<?= $rowSelectItem[3] ?>" name="valor" placeholder="Ex.: 5,99" title="Valor do item">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-sm btn-success" type="submit" name="cadastrar" form="formulario-item"> Cadastrar </button>
        </div>
    </div>
</div>

<script src="<?= BASED ?>/estoque/js/estoque.js"></script>