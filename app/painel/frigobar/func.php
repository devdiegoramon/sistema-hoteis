<?php
// Aqui define a função uma única vez
function obterProdutosEstoque($offset, $limit) {
    global $con;
    $sql = "SELECT item, categoria, valorunitario FROM estoque WHERE ativo = 's' LIMIT {$limit} OFFSET {$offset}";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        return $result;
    }
    return false;
}
?>
