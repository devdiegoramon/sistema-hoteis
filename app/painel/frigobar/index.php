<?php
session_start();

// Initialize cart
$_SESSION['carrinho'] = $_SESSION['carrinho'] ?? [];

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

// Function to get products from stock with quantity check
function getStockProducts($offset, $limit) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE ativo = 's' LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add product to cart
if (isset($_POST['add_to_cart'], $_POST['iditem'], $_POST['quantidade'])) {
    $iditem = (int)$_POST['iditem'];
    $quantidade = (int)$_POST['quantidade'];

    if ($quantidade > 0) {
        $stmt = $pdo->prepare("SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE iditem = :iditem AND quantidade >= :quantidade AND ativo = 's'");
        $stmt->execute([':iditem' => $iditem, ':quantidade' => $quantidade]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            $found = false;
            foreach ($_SESSION['carrinho'] as &$item) {
                if ($item['iditem'] == $iditem) {
                    $item['quantidade'] += $quantidade;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $produto['quantidade'] = $quantidade;
                $_SESSION['carrinho'][] = $produto;
            }

            $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - :quantidade WHERE iditem = :iditem");
            $stmt->execute([':quantidade' => $quantidade, ':iditem' => $iditem]);
            
            header("Location: index.php");
            exit;
        }
    }
}

// Clear cart
if (isset($_POST['limpar_carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade + :quantidade WHERE iditem = :iditem");
        $stmt->execute([':quantidade' => $item['quantidade'], ':iditem' => $item['iditem']]);
    }
    $_SESSION['carrinho'] = [];
    header("Location: index.php");
    exit;
}

$produtos = getStockProducts(0, 50); // Show more items at once for mobile scroll

// Calculate cart totals
$quantidade_total = 0;
$valor_total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $quantidade_total += $item['quantidade'];
    $valor_total += $item['quantidade'] * $item['valorunitario'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frigobar - Painel do Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0066FF;
            --primary-dark: #0052CC;
            --success: #10B981;
            --danger: #EF4444;
            --background: #F3F4F6;
            --surface: #FFFFFF;
            --text: #111827;
            --text-secondary: #6B7280;
            --border: #E5E7EB;
            --radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            padding: 16px;
            max-width: 100%;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 24px;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text);
            text-align: center;
        }

        .products-grid {
            display: grid;
            gap: 16px;
            margin-bottom: 24px;
        }

        .product-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .product-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .product-category {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 12px;
        }

        .product-price {
            font-weight: 600;
            color: var(--primary);
            font-size: 18px;
            margin-bottom: 16px;
        }

        .add-to-cart-form {
            display: flex;
            gap: 8px;
        }

        .quantity-input {
            width: 80px;
            padding: 8px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .add-button {
            flex: 1;
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .add-button:hover {
            background: var(--primary-dark);
        }

        .cart {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 16px;
            margin-top: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .cart-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 500;
        }

        .cart-item-quantity {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .cart-item-price {
            font-weight: 500;
            color: var(--primary);
        }

        .cart-total {
            margin-top: 16px;
            text-align: right;
            font-weight: 600;
            font-size: 18px;
        }

        .cart-actions {
            margin-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .buy-button {
            background: var(--success);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .clear-button {
            background: var(--danger);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .empty-cart {
            text-align: center;
            color: var(--text-secondary);
            padding: 24px 0;
        }

        @media (min-width: 640px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .container {
                max-width: 1024px;
                margin: 0 auto;
            }

            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .title {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1 class="title">Itens do Frigobar</h1>
        </header>

        <div class="products-grid">
            <?php foreach ($produtos as $produto): ?>
                <div class="product-card">
                    <div class="product-name"><?= htmlspecialchars($produto['item']) ?></div>
                    <div class="product-category"><?= htmlspecialchars($produto['categoria']) ?></div>
                    <div class="product-price">R$ <?= number_format($produto['valorunitario'], 2, ',', '.') ?></div>
                    <?php if ($produto['quantidade'] > 0): ?>
                        <form class="add-to-cart-form" method="POST">
                            <input type="hidden" name="iditem" value="<?= $produto['iditem'] ?>">
                            <input type="number" 
                                   class="quantity-input" 
                                   name="quantidade" 
                                   min="1" 
                                   max="<?= $produto['quantidade'] ?>" 
                                   value="1" 
                                   required>
                            <button type="submit" name="add_to_cart" class="add-button">
                                Adicionar
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="product-unavailable">Indisponível</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart">
            <h2 class="cart-title">Carrinho</h2>
            <?php if (!empty($_SESSION['carrinho'])): ?>
                <?php foreach ($_SESSION['carrinho'] as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name"><?= htmlspecialchars($item['item']) ?></div>
                            <div class="cart-item-quantity">Quantidade: <?= $item['quantidade'] ?></div>
                        </div>
                        <div class="cart-item-price">
                            R$ <?= number_format($item['valorunitario'] * $item['quantidade'], 2, ',', '.') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="cart-total">
                    Total: R$ <?= number_format($valor_total, 2, ',', '.') ?>
                </div>
                
                <div class="cart-actions">
                    <button class="buy-button">Finalizar Compra</button>
                    <form method="POST">
                        <button type="submit" name="limpar_carrinho" class="clear-button">
                            Limpar Carrinho
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="empty-cart">
                    Seu carrinho está vazio
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>