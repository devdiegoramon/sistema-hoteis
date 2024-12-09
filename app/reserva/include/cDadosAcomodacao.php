<?php

if (!isset($_POST['start']) || !isset($_POST['end']) ||
        empty($_POST['start']) || empty($_POST['end']) ||
        strlen($_POST['start']) != 10 || strlen($_POST['end']) != 10 ||
        !isset($_POST['idacomodacao']) || empty($_POST['idacomodacao']) ||
        !is_numeric($_POST['idacomodacao'])) {

    header('Location: index.php?text=Preencha todos os campos corretamente&type=2');
    return;
}

if (isset($_POST['idacomodacao'])) {
    include "../../config/connMysql.php";
    include "../../config/config.php";
    include "../../include/func.php";
    $idacomodacao = $_POST['idacomodacao'];
    $start = dataAmerica($_POST['start']); //DATA DE ENTRADA (FORMATO AMERICANO)
    $end = dataAmerica($_POST['end']); //DATA DE SAIDA (FORMATO AMERICA)
    $start = new DateTime($start);
    $end = new DateTime($end);
    $dias = $start->diff($end);
    $dias = $dias->days; //CALCULANDO DIAS DE ESTADIA

    if ($dias == 0) {
        $dias = 1;
    }

    $sql = "SELECT capacidade,
                   valor
            FROM acomodacao WHERE idacomodacao = $idacomodacao";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);

    $tempTotal = $dias * $row[1];
    $total = converteReal($tempTotal);
    $valorDiarias = converteReal($row[1]);

    //CRIANDO JSON PARA PODER FILTRAR AS INFORMAÇÔES DA REQUISIÇÂO
    $dados = array('valordiarias' => $valorDiarias, 'diarias' => $dias, 'capacidade' => $row[0]);
    echo json_encode($dados);
} else {
    $text = "Sem acesso";
    header("Location: ../../../index.php?text=$text&type=1");
}    