<?php
include "../../config/connMysql.php";
include "../../config/config.php";
if (!isset($_GET['validar'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    return;
}
?>

<div class="modal-header">
    <h5 class="modal-title">Adicionar novo item</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body col-12">
    <div id="resposta-acomodacao"> </div>
    <form enctype="multipart/form-data" class="g-2 formulario-item" id="formulario-item" action="include/gItem.php" method="POST">
        <div id="linha-adicionar"> 
            <div class="linha zebrar row p-3 impar" id="linha_1"> 
                <div class="col-md-3">
                    <label class="form-label" for="categoria"> Categoria </label>
                    <select class="form-select form-select-sm" required name="categoria[]" title="Categoria do item">
                        <?php
                        $sqlConsultaCat = "SELECT nome FROM categoriaestoque WHERE ativo = 's'";
                        $resultConsultaCat = mysqli_query($con, $sqlConsultaCat);
                        if (mysqli_num_rows($resultConsultaCat) > 0) {
                            echo '<option selected value="" class="form-option">Selecione uma opção</option>';
                            while ($rowCat = mysqli_fetch_array($resultConsultaCat)) {
                                echo '<option value="' . $rowCat[0] . '" class="form-option">' . $rowCat[0] . '</option>';
                            }
                        } else {
                            echo '<option value="" class="form-option">Nenhuma opção cadastrada</option>';
                        }
                        mysqli_close($con);
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="nome"> Nome </label>
                    <input class="form-control form-control-sm" required type="text" name="nome[]"  placeholder="Ex.: Heineken 200ml" title="Nome do item">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="quantidade"> Quantidade </label>
                    <input class="form-control form-control-sm" required type="number" name="quantidade[]" min="1" placeholder="Ex.: 100" title="Adicionar quantidade">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="valor"> Valor </label>
                    <div class="input-group input-group-sm"> 
                        <span class="input-group-text"> R$ </span>
                        <input class="form-control form-control-sm money" required type="text" name="valor[]" placeholder="Ex.: 5,99" title="Valor do item">
                    </div>
                </div>
                <div class='col-md-1' style="margin-top: 35px"> 
                    <a class="text-danger btn-del"><i class="fa-regular fa-trash-can"></i> </a>
                </div>
            </div>
        </div>
        <input type="number" id='numLinhas' name="linhas" value="1" hidden=""> 
        <div class='mt-2'> 
            <a class='btn-add text-black-50 add'><i class="fa-solid fa-plus"></i> Adicionar novo item </a>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-sm btn-success" type="submit" name="cadastrar" form="formulario-item"><i class="fa-solid fa-floppy-disk"></i> Cadastrar </button>
</div>

<script src="<?= BASED ?>/estoque/js/estoque.js"></script>
<script>

    //ADD LINHA
    num_linha = 1; //CONTADOR
    $(".add").click(function () {
        num_linha++;
        let conteudo = '<div class="mt-2 p-3 row zebrar linha" id="linha_' + num_linha + '">' + $(".linha:first").html() + ' </div>';
        $('#linha-adicionar').append(conteudo);
        $('.btn-del:last').attr('onclick', 'excluirLinha("linha_' + num_linha + '")');
        $('.money').mask('000.000,00', {reverse: true});
        $('#numLinhas').val(num_linha);
    });

    function excluirLinha(linha) {
        $("#" + linha).remove();
    }
</script>