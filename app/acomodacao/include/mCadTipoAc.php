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
    <h5 class="modal-title" id="exampleModalLabel">Tipo de acomodação</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div id="resposta">  </div>
    <form class="col-md-12 g-2 formulario-tipo" id="formulario-tipo" method="POST">
        <div class="input-group">
            <input class="form-control form-control-sm mr-3" type="text" name="nome" id="nome-tipo" placeholder="Ex.: Suite">
            <button class="btn btn-sm btn-success accordion" type="button" name="cadastrar" id="cadastrar-tipo"> Cadastrar </button>
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
        <tbody id="tbody-tipoAcomodacao"></tbody>
    </table>
</div>

<script src="<?= BASED ?>/acomodacao/js/acomodacao.js"></script>