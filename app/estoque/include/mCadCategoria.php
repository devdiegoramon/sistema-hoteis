<?php
include "../../config/connMysql.php";
include "../../config/config.php";
if (!isset($_GET['validar'])) {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
    return;
}
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Categorias do estoque</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="resposta-categoria"> </div>
            <form class="col-md-12 g-2 formulario-categoria" id="formulario-categoria" method="POST">
                <div class="input-group">
                    <input class="form-control form-control-sm mr-3" type="text" name="nome" id="nome-categoria" placeholder="Ex.: Bebidas">
                    <button class="btn btn-sm btn-success accordion" type="button" name="cadastrar" id="cadastrar-categoria"> Cadastrar </button>
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Nome </th>
                        <th> Status </th>
                        <th> Editar </th>
                    </tr>
                </thead>
                <tbody id="tbody-categoria"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= BASED ?>/estoque/js/estoque.js"></script>