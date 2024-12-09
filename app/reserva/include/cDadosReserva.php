<?php

if (!isset($_GET['id'])) {
    header("Location: ../../index.php?text=Sem Acesso&type=1");
}

$idreserva = $_GET['id'];
$sqlReserva = "SELECT r.idreserva,
                        r.idacomodacao,
                        r.idcliente,
                        r.quantidadehospedes,
                        r.entradaprevista,
                        r.saidaprevista,
                        r.datacheckin,
                        r.horacheckin,
                        r.datacheckout,
                        r.horacheckout,
                        r.datag,
                        r.horag,
                        r.status,
                        r.obs,
                        a.nome,
                        a.numero,
                        a.valor,
                        c.nome,
                        c.cpf,
                        con.valorestadia,
                        con.valoritens,
                        con.status,
                        con.idconsumo,
                        r.valordiaria
                FROM reserva r       
                LEFT JOIN cliente c ON (r.idcliente = c.idcliente)
                LEFT JOIN acomodacao a ON (r.idacomodacao = a.idacomodacao)
                LEFT JOIN consumo con ON (con.idreserva = r.idreserva)
                WHERE r.idreserva = {$idreserva}";
$resultReserva = mysqli_query($con, $sqlReserva);
$rowReserva = mysqli_fetch_array($resultReserva);

if (mysqli_num_rows($resultReserva) == 0) {
    header("Location: ../../index.php?text=Sem Acesso&type=1");
}

$sqlFrigobar = "SELECT idfrigobar,
                           modelo,
                           patrimonio,
                           ativo
                    FROM frigobar WHERE idacomodacao = {$rowReserva[1]}
                    AND ativo = 's'";
$resultFrigobar = mysqli_query($con, $sqlFrigobar);
$totalFrigobar = mysqli_num_rows($resultFrigobar);

if ($rowReserva['datacheckin'] == null) {
    $statusCheckIn = "<span class='my-badge badge bg-warning text-dark'> Check-in pendente </span>";
} else {
    $statusCheckIn = "<span class='my-badge badge bg-success'> Check-in realizado </span>";
}

if ($rowReserva['datacheckout'] == null) {
    $statusCheckOut = "<span class='my-badge badge bg-warning text-dark'> Check-out pendente </span>";
} else {
    $statusCheckOut = "<span class='my-badge badge bg-success'> Check-out realizado </span>";
}

$hidden = "";
if ($rowReserva['datacheckin'] == null) {
    $hidden = "hidden='true'";
}