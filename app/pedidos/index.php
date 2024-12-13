<?php
include "../config/config.php";
include "../config/connMysql.php";
include "../include/func.php";
include "../include/components.php";

// Fetch active orders from the database
$sql = "SELECT p.id, p.data_pedido, p.status, c.nome as cliente, a.nome as acomodacao, p.valor_total
        FROM pedidos p
        JOIN cliente c ON p.idcliente = c.idcliente
        JOIN acomodacao a ON p.idacomodacao = a.idacomodacao
        ORDER BY p.data_pedido DESC";
$result = mysqli_query($con, $sql);

// Fetch historical orders from the database
$sql_historico = "SELECT p.id, p.data_pedido, p.data_conclusao, p.status, c.nome as cliente, a.nome as acomodacao, p.valor_total
                  FROM pedidos_historico p
                  JOIN cliente c ON p.idcliente = c.idcliente
                  JOIN acomodacao a ON p.idacomodacao = a.idacomodacao
                  ORDER BY p.data_pedido DESC";
$result_historico = mysqli_query($con, $sql_historico);

// Handle PDF export
if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    require('../vendor/fpdf/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'Relatório de Pedidos', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);

    // Fetch orders for PDF
    $sql_pdf = "SELECT p.id, p.data_pedido, p.status, c.nome as cliente, a.nome as acomodacao, p.valor_total
                FROM pedidos p
                JOIN cliente c ON p.idcliente = c.idcliente
                JOIN acomodacao a ON p.idacomodacao = a.idacomodacao
                ORDER BY p.data_pedido DESC";
    $result_pdf = mysqli_query($con, $sql_pdf);

    while ($row = mysqli_fetch_assoc($result_pdf)) {
        $pdf->Cell(30, 10, 'ID: ' . $row['id'], 0, 0);
        $pdf->Cell(40, 10, 'Cliente: ' . $row['cliente'], 0, 0);
        $pdf->Cell(40, 10, 'Acomodação: ' . $row['acomodacao'], 0, 0);
        $pdf->Cell(40, 10, 'Valor: R$ ' . number_format($row['valor_total'], 2, ',', '.'), 0, 1);
    }

    $pdf->Output('D', 'relatorio_pedidos.pdf');
    exit;
}

// Handle date filtering
$filter_sql = "";
if (isset($_GET['data_inicio']) && isset($_GET['data_fim'])) {
    $data_inicio = $_GET['data_inicio'];
    $data_fim = $_GET['data_fim'];
    $filter_sql = " WHERE p.data_pedido BETWEEN '$data_inicio' AND '$data_fim 23:59:59'";
}

$sql_filtered = "SELECT p.id, p.data_pedido, p.status, c.nome as cliente, a.nome as acomodacao, p.valor_total
                 FROM pedidos p
                 JOIN cliente c ON p.idcliente = c.idcliente
                 JOIN acomodacao a ON p.idacomodacao = a.idacomodacao" . $filter_sql . " ORDER BY p.data_pedido DESC";
$result_filtered = mysqli_query($con, $sql_filtered);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $iconeSite ?>
    <link href="<?= BASED ?>/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="<?= BASED ?>/assets/css/sidebar.css" type="text/css" rel="stylesheet">
    <link href="<?= BASED ?>/assets/css/style.css" type="text/css" rel="stylesheet">
    <link href="<?= BASED ?>/assets/vendor/fontawesome-5.15.4/css/all.min.css" type="text/css" rel="stylesheet">
    <link href="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.css" type="text/css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" type="text/css" rel="stylesheet">
    <title>Pedidos do Frigobar</title>
</head>
<body id="body-pd" class="body-pd">
    <?php include "../include/sidebar.php" ?>
    <div class="container mt-4">
        <h1 class="mb-4">Pedidos do Frigobar</h1>

        <!-- Filter Form -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="data_inicio">Data Início:</label>
                    <input type="date" name="data_inicio" class="form-control" value="<?= isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="data_fim">Data Fim:</label>
                    <input type="date" name="data_fim" class="form-control" value="<?= isset($_GET['data_fim']) ? $_GET['data_fim'] : '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
                </div>
                <div class="col-md-2">
                    <a href="?export=pdf" class="btn btn-success mt-4">Exportar PDF</a>
                </div>
            </div>
        </form>

        <!-- Active Orders Table -->
        <div class="card">
            <div class="card-body">
                <h2>Pedidos Ativos</h2>
                <table id="pedidosTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Acomodação</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_filtered)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['data_pedido'])) ?></td>
                                <td><?= $row['cliente'] ?></td>
                                <td><?= $row['acomodacao'] ?></td>
                                <td>R$ <?= number_format($row['valor_total'], 2, ',', '.') ?></td>
                                <td><?= $row['status'] ?></td>
                                <td>
                                    <a href="visualizar_pedido.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Visualizar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Historical Orders Table -->
        <div class="card mt-4">
            <div class="card-body">
                <h2>Histórico de Pedidos</h2>
                <table id="historicoTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data do Pedido</th>
                            <th>Data de Conclusão</th>
                            <th>Cliente</th>
                            <th>Acomodação</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_historico)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['data_pedido'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['data_conclusao'])) ?></td>
                                <td><?= $row['cliente'] ?></td>
                                <td><?= $row['acomodacao'] ?></td>
                                <td>R$ <?= number_format($row['valor_total'], 2, ',', '.') ?></td>
                                <td><?= $row['status'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="<?= BASED ?>/assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?= BASED ?>/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASED ?>/assets/vendor/data-table/jquery.dataTables.min.js"></script>
    <script src="<?= BASED ?>/assets/vendor/data-table/dataTables.bootstrap5.min.js"></script>
    <script src="<?= BASED ?>/assets/js/sidebar.js"></script>
    <script>
        $(document).ready(function() {
            $('#pedidosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                }
            });
            $('#historicoTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                }
            });
        });
    </script>
</body>
</html>