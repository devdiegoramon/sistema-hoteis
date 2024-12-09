<?php

if (isset($_GET['id'])) {

    //Dados da acomodação
    $sqlDadosAc = "SELECT a.idtipoacomodacao, 
                          i.nome,
                          a.nome,
                          a.numero,
                          a.valor,
                          a.capacidade,
                          a.descricao,
                          a.ativo,
                          a.datag
                    FROM acomodacao a
                    INNER JOIN tipoacomodacao i ON (a.idtipoacomodacao = i.idtipoac)
                    WHERE a.idacomodacao = {$idacomodacao}";
    $resultDadosAc = mysqli_query($con, $sqlDadosAc);
    $rowDadosAc = mysqli_fetch_array($resultDadosAc);
   
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}    