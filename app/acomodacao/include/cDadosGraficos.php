<?php

//if (isset($_POST['idacomodacao'])) {
include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";

$dados = [
    'label' => [],
    'value' => []
];

$primeiroDia = date("Y-m-01");
$i = 1;
for ($i; $i <= 12; $i++) {
    $ultimoDia = new DateTime("$primeiroDia");
    $ultimoDia = $ultimoDia->format('Y-m-t');

    $sqlReservaMes = "SELECT count(1)
                      FROM reserva
                      WHERE (entradaprevista >= date('{$primeiroDia}') and saidaprevista <= date('{$ultimoDia}')
                      OR saidaprevista >= date('{$primeiroDia}') and entradaprevista <= date('{$ultimoDia}'))";
    $resultReserva = mysqli_query($con, $sqlReservaMes);
    $rowResultReserva = mysqli_fetch_array($resultReserva);
    $dados['label'][] = $primeiroDia;
    $dados['value'][] = $rowResultReserva[0];

    $primeiroDia = date("Y-m-01", strtotime("-$i months", strtotime(date('Y-m-d'))));
}

echo "<br>";
echo '<pre>';
print_r($dados);
echo '</pre>';

mysqli_close($con);
//} else {
//    $text = "Sem acesso";
//    header("Location: ../../../index.php?text=$text&type=1");
//}


