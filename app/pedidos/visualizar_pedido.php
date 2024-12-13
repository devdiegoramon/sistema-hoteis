<?php
session_start();

// Database connection
$config = [
    'host' => 'localhost',
    'user' => 'admin',
    'pass' => '',
    'db'   => 'sistema_hoteis_prosync'
];

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['db']};charset=utf8", $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Get the order ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do pedido inválido.");
}

$pedido_id = (int)$_GET['id'];

// Fetch the order details from the database
$stmt = $pdo->prepare("SELECT p.*, c.nome AS cliente_nome, a.nome AS acomodacao_nome 
                       FROM pedidos p
                       JOIN cliente c ON p.idcliente = c.idcliente
                       JOIN acomodacao a ON p.idacomodacao = a.idacomodacao
                       WHERE p.id = :pedido_id");
$stmt->execute([':pedido_id' => $pedido_id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("Pedido não encontrado.");
}

// Fetch the items of the order
$stmt = $pdo->prepare("SELECT ic.*, e.item AS nome_item 
                       FROM itensconsumidos ic
                       JOIN estoque e ON ic.iditem = e.iditem
                       WHERE ic.idpedido = :pedido_id");
$stmt->execute([':pedido_id' => $pedido_id]);
$itens_pedido = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status change to "Concluído"
if (isset($_POST['concluir_pedido'])) {
    // Update the status to "Concluído"
    $stmt = $pdo->prepare("UPDATE pedidos SET status = 'Concluído' WHERE id = :pedido_id");
    $stmt->execute([':pedido_id' => $pedido_id]);

    // Move the order to the history table
    $stmt = $pdo->prepare("INSERT INTO pedidos_historico (id, data_pedido, data_conclusao, status, idcliente, idacomodacao, valor_total)
                           VALUES (:id, :data_pedido, NOW(), :status, :idcliente, :idacomodacao, :valor_total)");
    $stmt->execute([
        ':id' => $pedido['id'],
        ':data_pedido' => $pedido['data_pedido'],
        ':status' => 'Concluído',
        ':idcliente' => $pedido['idcliente'],
        ':idacomodacao' => $pedido['idacomodacao'],
        ':valor_total' => $pedido['valor_total']
    ]);

    // Delete the items from the itensconsumidos table
    $stmt = $pdo->prepare("DELETE FROM itensconsumidos WHERE idpedido = :pedido_id");
    $stmt->execute([':pedido_id' => $pedido_id]);

    // Remove the order from the active orders table
    $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = :pedido_id");
    $stmt->execute([':pedido_id' => $pedido_id]);

    // Redirect to the order list
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Pedido #<?= $pedido['id'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1 class="title">Pedido #<?= $pedido['id'] ?></h1>
        </header>

        <div class="order-details">
            <h2>Detalhes do Pedido</h2>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente_nome']) ?></p>
            <p><strong>Acomodação:</strong> <?= htmlspecialchars($pedido['acomodacao_nome']) ?></p>
            <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($pedido['status']) ?></p>
            <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></p>
        </div>

        <div class="order-items">
            <h2>Itens do Pedido</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens_pedido as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome_item']) ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td>R$ <?= number_format($item['valorunitario'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($item['quantidade'] * $item['valorunitario'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="actions">
            <?php if ($pedido['status'] === 'Pendente'): ?>
                <form method="POST">
                    <button type="submit" name="concluir_pedido" class="complete-button">
                        Concluir Pedido
                    </button>
                </form>
            <?php endif; ?>
            <a href="index.php" class="back-button">Voltar para a Lista de Pedidos</a>
        </div>
    </div>
</body>
</html>