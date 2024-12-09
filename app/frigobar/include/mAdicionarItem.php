<?php
if (!isset($_POST['id'])) {
    header('Location: ../../../index.php?text=Sem acesso&type=1');
}
include "../../config/connMysql.php";
include "../../config/config.php";
$idfrigobar = $_POST['id'];

//SELECIONA OS DADOS DO ITEM
$sqlItem = "SELECT iditem, item, quantidade FROM estoque WHERE ativo = 's'";
$resultItem = mysqli_query($con, $sqlItem);
?>
<div class="modal-header">
    <h5 class="modal-title "> Adicionar item ao frigobar #<?= $idfrigobar ?> </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body col-12">
    <form enctype="multipart/form-data" class="row g-2 formulario-acomodacao" id="formulario-adicionarItem" action="include/gItemFrigobar.php" method="POST">
        <input type="number" name="idfrigobar" hidden="" value="<?= $idfrigobar ?>"> 
        <div class="col-md-3">
            <label class="form-label"> Categoria </label>
            <select onchange="consultaDadosItem(<?= $idfrigobar ?>)" required name="categoria" class="form-select form-select-sm" id="select-categoria">
                <option value="todas"> Todas Categorias </option>
                <?php
                $sqlCategoria = "SELECT nome FROM categoriaestoque WHERE ativo = 's'";
                $resultCategoria = mysqli_query($con, $sqlCategoria);
                while ($rowCategoria = mysqli_fetch_array($resultCategoria)) {
                    echo '<option value="' . $rowCategoria[0] . '"> ' . $rowCategoria[0] . ' </option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label"> Item </label>
            <select required name="item" onchange="consultaQuantidade()" class="form-select form-select-sm" id="select-item">
                <option value=""> Selecione uma opção </option>
                <?php
                while ($rowItem = mysqli_fetch_array($resultItem)) {
                    echo '<option value="' . $rowItem[0] . '"> ' . $rowItem[1] . ' </option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label"> Em estoque</label> 
            <input required name="estoque" class="form-control form-control-sm" readonly min="0" type="number" id="estoque"> 
        </div>
        <div class="col-md-3">
            <label class="form-label"> Adicionar </label> 
            <input required name="quantidade" class="form-control form-control-sm" disabled min="1" type="number" id="quantidadeItem"> 
        </div>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-sm btn-success" type="submit" name="adicionar" form="formulario-adicionarItem"><i class="fa-solid fa-cart-plus"></i> Adicionar </button>
</div>

<script src="<?= BASED ?>/frigobar/js/frigobar.js"></script>