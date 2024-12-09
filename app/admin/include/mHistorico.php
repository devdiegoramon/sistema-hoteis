<?php
include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";
?>

<div class="modal-header">
    <h5 class="modal-title "> Histórico de alterações </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body col-12">
    <div class="table-responsive">
        <table style="font-size: 80%" class="table table-hover table-striped datatable" > 
            <thead> 
                <tr> 
                    <th class="width-0"> # </th>
                    <th class="width-0"> Tabela </th>
                    <th class="width-0"> Ação </th>
                    <th> Descrição </th>
                </tr>
            </thead>
            <tbody> 
                <?php
                $sql = "SELECT idlog,
                               acao,
                               obs,
                               tabela
                        FROM log
                        ORDER by idlog desc";
                $result = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr> 
                        <td class="width-0"> <?= $row[0] ?> </td>
                        <td class="width-0"> <?= $row[3] ?> </td>
                        <td class="width-0"> <?= ucfirst($row[1]) ?> </td>
                        <td> <?= $row[2] ?> </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
</div>
