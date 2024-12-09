<?php
if (isset($_POST['id'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";

    $idfrigobar = $_POST['id'];
    $sql = "SELECT f.modelo,
                   f.patrimonio,
                   f.ativo,
                   a.nome,
                   a.numero
            FROM frigobar f 
            INNER JOIN acomodacao a ON (f.idacomodacao = a.idacomodacao)
            WHERE f.idfrigobar = {$idfrigobar}";
    $resultSql = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($resultSql);
    $sqlItensFrig = "SELECT i.iditemfrigobar,
                            i.iditem,
                            i.quantidade,
                            e.item,
                            e.categoria,
                            e.valorunitario
                     FROM itemfrigobar i
                     INNER JOIN estoque e ON (i.iditem = e.iditem) 
                     WHERE i.idfrigobar = {$idfrigobar}";
    $resultSqlItens = mysqli_query($con, $sqlItensFrig);
    ?>
    <h5 class="mb-5" style="width:100%; text-align: center;"> Frigobar <?= $row[0] ?></h5>
    <div class="col-md-6 boder-1" >
        <button class="col-md-2 btn btn-primary" onclick="todosFrig()">  < Voltar </button>
        <form class="row g-3" style="border:1px solid #C8C8C8;">
            <div class="col-md-12"> 
                <label class="form-label"> Modelo </label>
                <input class="form-control" disabled value="<?= $row[0] ?>" type="text"> 
            </div>
            <div class="col-md-6"> 
                <label class="form-label"> Patrimônio </label>
                <input class="form-control" disabled value="<?= $row[1] ?>" type="text"> 
            </div>
            <div class="col-md-6"> 
                <label class="form-label"> Acomodação </label>
                <input class="form-control" disabled value="<?= $row[3] ?>" type="text"> 
            </div>
            <div class="col-md-6"> 
                <label class="form-label"> Número (Acomodação) </label>
                <input class="form-control" disabled value="<?= $row[4] ?>" type="text"> 
            </div>
            <div class="col-md-6 mb-3"> 
                <label class="form-label"> Total de itens </label>
                <input class="form-control" disabled value="55" type="text"> 
            </div>
        </form>
    </div>
    <div class="col-md-6" > 
        <div class="row"> 
            <button class="col-4"> Adicionar Item </button>
            <form class="row g-3"> 
                <div class="col-md-6">
                    <label class="form-label"> Categoria</label>
                    <select class="form-select"> 
                        <option> Selecione uma opção </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"> Item </label>
                    <select class="form-select"> 
                        <option> Selecione uma categoria </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"> Quantidade </label>
                    <select class="form-select"> 
                        <option> Selecione uma categoria </option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-5"> 
        <div class="table-responsive" > 
            <table class="table table-striped" style="width:100%;"> 
                <thead> 
                    <tr>
                        <th> # </th>
                        <th> Item </th>
                        <th> Categoria </th>
                        <th> Quantidade </th>
                        <th> Valor unitario </th>
                    </tr>
                </thead>
                <tbody> 
                    <?php while ($rowSqlItens = mysqli_fetch_array($resultSqlItens)) { ?>
                        <tr> 
                            <td> <?= $rowSqlItens[0] ?> </td>
                            <td> <?= $rowSqlItens[3] ?> </td>
                            <td> <?= $rowSqlItens[2] ?> </td>
                            <td> <?= $rowSqlItens[2] ?> </td>
                            <td> <?= $rowSqlItens[5] ?> </td>
                        </tr>   
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    mysqli_close($con);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}
?>