<?php
// 1. Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Database Connection (MySQLi)
$con = mysqli_connect('localhost', 'root', '', 'sistema_hoteis_prosync');

if (!$con) {
    // Log the detailed error for server admin/developer
    error_log("Erro fatal de conexão MySQLi: " . mysqli_connect_error());
    // Provide a user-friendly message and stop script execution
    die("Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde ou contate o suporte.");
}

if (!mysqli_set_charset($con, 'utf8')) {
    error_log("Erro ao configurar charset MySQLi: " . mysqli_error($con));
    die("Erro na configuração do banco de dados. Contate o suporte.");
}

// 3. Function definitions (refactored to MySQLi)

/**
 * Limpa o carrinho e devolve os itens ao estoque
 */
function limparCarrinho()
{
    global $con; // Use MySQLi connection

    if (!empty($_SESSION['carrinho'])) {
        mysqli_begin_transaction($con); // Start transaction

        try {
            foreach ($_SESSION['carrinho'] as $item) {
                $stmt = mysqli_prepare($con, "UPDATE estoque SET quantidade = quantidade + ? WHERE iditem = ?");
                if (!$stmt) {
                    throw new Exception("MySQLi prepare error (update estoque): " . mysqli_error($con));
                }
                mysqli_stmt_bind_param($stmt, "ii", $item['quantidade'], $item['iditem']);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("MySQLi execute error (update estoque): " . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            }

            $_SESSION['carrinho'] = [];

            setcookie('carrinho', '', time() - 3600, '/', '', false, true);
            setcookie('cart_timer', '', time() - 3600, '/', '', false, true);

            mysqli_commit($con); // Commit transaction
        } catch (Exception $e) {
            mysqli_rollback($con); // Rollback transaction on error
            error_log("Erro ao limpar o carrinho: " . $e->getMessage());
        }
    }
}

/**
 * Inicializa o carrinho e o timer
 */
function inicializarCarrinho()
{
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    if (empty($_COOKIE['cart_timer'])) {
        // Set timer for 60 seconds from now, and cookie expiry also in 60 seconds
        $expiryTime = time() + 60;
        setcookie('cart_timer', $expiryTime, $expiryTime, '/', '', false, true);
    }
}

/**
 * Salva o carrinho em um cookie
 */
function salvarCarrinhoEmCookie()
{
    // Cookie expires in 1 hour (3600 seconds)
    setcookie('carrinho', json_encode($_SESSION['carrinho'], JSON_UNESCAPED_UNICODE), time() + 3600, '/', '', false, true);
}

/**
 * Busca os produtos ativos no estoque com paginação
 */
function buscarProdutosEstoque($offset, $limit)
{
    global $con; // Use MySQLi connection
    $produtos = [];

    $sql = "SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE ativo = 's' LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result) {
                $produtos = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_free_result($result);
            } else {
                error_log("Erro ao obter resultado da busca de produtos: " . mysqli_stmt_error($stmt));
            }
        } else {
            error_log("Erro ao executar busca de produtos: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Erro ao preparar a query de busca de produtos: " . mysqli_error($con));
    }
    return $produtos;
}

/**
 * Adiciona um item ao carrinho
 */
function adicionarAoCarrinho($idItem, $quantidade)
{
    global $con; // Use MySQLi connection

    if ($quantidade <= 0 || $idItem <= 0) {
        return;
    }

    // Check product availability and details
    $sqlSelect = "SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE iditem = ? AND quantidade >= ? AND ativo = 's'";
    $stmtSelect = mysqli_prepare($con, $sqlSelect);

    if (!$stmtSelect) {
        error_log("Erro ao preparar select em adicionarAoCarrinho: " . mysqli_error($con));
        return;
    }

    mysqli_stmt_bind_param($stmtSelect, "ii", $idItem, $quantidade);
    if (!mysqli_stmt_execute($stmtSelect)) {
        error_log("Erro ao executar select em adicionarAoCarrinho: " . mysqli_stmt_error($stmtSelect));
        mysqli_stmt_close($stmtSelect);
        return;
    }

    $resultSelect = mysqli_stmt_get_result($stmtSelect);
    $produto = mysqli_fetch_assoc($resultSelect);
    mysqli_free_result($resultSelect);
    mysqli_stmt_close($stmtSelect);

    if ($produto) {
        $encontrado = false;
        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['iditem'] == $idItem) {
                // Here, we should check if adding $quantidade exceeds $produto['quantidade'] (original stock)
                // This logic might need refinement based on whether $item['quantidade'] is total in cart vs. original stock
                $item['quantidade'] += $quantidade;
                $encontrado = true;
                break;
            }
        }
        unset($item); // Unset reference

        if (!$encontrado) {
            $produto['quantidade'] = $quantidade; // This sets product's quantity IN CART to the added amount
            $_SESSION['carrinho'][] = $produto;
        }

        // Update stock quantity
        $sqlUpdate = "UPDATE estoque SET quantidade = quantidade - ? WHERE iditem = ?";
        $stmtUpdate = mysqli_prepare($con, $sqlUpdate);
        if (!$stmtUpdate) {
            error_log("Erro ao preparar update em adicionarAoCarrinho: " . mysqli_error($con));
            // Potentially rollback or handle inconsistent state if part of a larger transaction
            return;
        }
        mysqli_stmt_bind_param($stmtUpdate, "ii", $quantidade, $idItem);
        if (!mysqli_stmt_execute($stmtUpdate)) {
            error_log("Erro ao executar update em adicionarAoCarrinho: " . mysqli_stmt_error($stmtUpdate));
            // Potentially rollback
        }
        mysqli_stmt_close($stmtUpdate);

        salvarCarrinhoEmCookie();
        $expiryTime = time() + 60; // Reset timer
        setcookie('cart_timer', $expiryTime, $expiryTime, '/', '', false, true);
    } else {
        // Product not found or not enough stock
        error_log("Tentativa de adicionar produto indisponível ou inexistente ao carrinho: ID $idItem, Qtd $quantidade");
    }
}

// 4. Lógica principal
if (!empty($_COOKIE['cart_timer']) && time() > $_COOKIE['cart_timer']) {
    limparCarrinho();
    // It's good to redirect or reload to reflect changes after clearing cart this way
    // header("Location: index.php");
    // exit;
    // For now, will let the JavaScript handle reload if it's the primary mechanism
}

inicializarCarrinho(); // Ensures session cart is an array and timer cookie exists if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'], $_POST['iditem'], $_POST['quantidade'])) {
        adicionarAoCarrinho((int)$_POST['iditem'], (int)$_POST['quantidade']);
        header("Location: index.php"); // Redirect to prevent form resubmission
        exit;
    }

    if (isset($_POST['limpar_carrinho'])) {
        limparCarrinho();
        // If this is an AJAX request (as indicated by the JavaScript fetch), send JSON
        // If it's a direct form submission, you might want to redirect
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['status' => 'success']);
        } else {
            // For non-AJAX, you might redirect
            // header("Location: index.php");
            echo json_encode(['status' => 'success']); // JS expects JSON
        }
        exit;
    }
}

$produtos = buscarProdutosEstoque(0, 50); // Fetch products for display
$quantidadeTotal = 0;
$valorTotal = 0;

if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        if (isset($item['quantidade'], $item['valorunitario'])) {
            $quantidadeTotal += $item['quantidade'];
            $valorTotal += $item['quantidade'] * $item['valorunitario'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frigobar - Painel do Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cartTimerCookie = <?= json_encode($_COOKIE['cart_timer'] ?? 0) ?>;
            let countdown = Math.max(0, cartTimerCookie - Math.floor(Date.now() / 1000));
            const countdownElement = document.getElementById('countdown');

            function updateCountdownDisplay() {
                if (!countdownElement) return;
                const minutes = Math.floor(countdown / 60);
                const seconds = countdown % 60;
                countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }

            updateCountdownDisplay(); // Initial display

            const timerInterval = setInterval(() => {
                countdown--;
                updateCountdownDisplay();

                if (countdown < 0) {
                    clearInterval(timerInterval);
                    // Ensure countdown doesn't show negative if element still exists
                    if(countdownElement) countdownElement.textContent = "0:00";

                    fetch('index.php', { // Assuming index.php is the current file
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                        body: new URLSearchParams({ limpar_carrinho: 'true' }), // Ensure body matches expected POST key
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    }).then(data => {
                        if (data.status === 'success') {
                            window.location.reload();
                        } else {
                            console.error('Failed to clear cart:', data);
                            // Optionally inform the user
                        }
                    }).catch(error => {
                        console.error('Error clearing cart via fetch:', error);
                        // Optionally inform the user about the fetch error
                    });
                }
            }, 1000);

            // Handle direct form submission for clearing cart (if JS is disabled or for the button)
            const clearCartButton = document.querySelector('button[name="limpar_carrinho"]');
            if (clearCartButton && clearCartButton.form) {
                clearCartButton.form.addEventListener('submit', function(event) {
                    // If you want to handle this via AJAX too, you can preventDefault and use fetch
                    // For now, it will submit as a normal POST if not handled by timer's fetch
                });
            }
        });
    </script>
</head>
<body>
<div class="container">
    <header class="header">
        <h1 class="title">Itens do Frigobar</h1>
        <div class="timer">
            <span id="countdown">1:00</span> </div>
        <div class="cart-icon">
            <a href="#cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?= isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0 ?></span>
            </a>
        </div>
    </header>

    <div class="products-grid">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="product-card">
                    <div class="product-name"><?= htmlspecialchars($produto['item'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="product-category"><?= htmlspecialchars($produto['categoria'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="product-price">R$ <?= number_format($produto['valorunitario'] ?? 0, 2, ',', '.') ?></div>
                    <?php if (isset($produto['quantidade']) && $produto['quantidade'] > 0): ?>
                        <form class="add-to-cart-form" method="POST" action="index.php">
                            <input type="hidden" name="iditem" value="<?= (int)($produto['iditem'] ?? 0) ?>">
                            <input type="number" name="quantidade" min="1" max="<?= (int)($produto['quantidade'] ?? 1) ?>" value="1" required class="product-quantity-input">
                            <button type="submit" name="add_to_cart" class="add-button">Adicionar</button>
                        </form>
                    <?php else: ?>
                        <div class="product-unavailable">Indisponível</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto encontrado no estoque.</p>
        <?php endif; ?>
    </div>

    <div class="cart" id="cart">
        <h2 class="cart-title">Carrinho</h2>
        <?php if (!empty($_SESSION['carrinho'])): ?>
            <?php foreach ($_SESSION['carrinho'] as $item): ?>
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="cart-item-name"><?= htmlspecialchars($item['item'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="cart-item-quantity">Quantidade: <?= (int)($item['quantidade'] ?? 0) ?></div>
                    </div>
                    <div class="cart-item-price">
                        R$ <?= number_format(($item['valorunitario'] ?? 0) * ($item['quantidade'] ?? 0), 2, ',', '.') ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="cart-summary">
                <div>Total de Itens: <?= $quantidadeTotal ?></div>
                <div>Valor Total: R$ <?= number_format($valorTotal, 2, ',', '.') ?></div>
            </div>
            <form method="POST" action="index.php"> <button type="submit" name="limpar_carrinho" class="clear-cart-button">Limpar Carrinho</button>
            </form>
        <?php else: ?>
            <div class="empty-cart">Seu carrinho está vazio.</div>
        <?php endif; ?>
    </div>
</div>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
        color: #333;
    }
    .container {
        max-width: 1200px;
        margin: auto;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }
    .title {
        font-size: 28px;
        color: #333;
        font-weight: 700;
    }
    .timer {
        font-size: 20px;
        color: #e74c3c;
        font-weight: 600;
    }
    .cart-icon a {
        text-decoration: none;
        color: #333;
        font-size: 20px;
        position: relative;
    }
    .cart-icon i {
        font-size: 28px;
    }
    .cart-count {
        background-color: #e74c3c;
        color: #fff;
        border-radius: 50%;
        padding: 3px 7px;
        font-size: 12px;
        position: absolute;
        top: -8px;
        right: -10px;
        font-weight: bold;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .product-card {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .product-category {
        color: #777;
        font-size: 14px;
        margin-bottom: 10px;
    }
    .product-price {
        font-size: 17px;
        color: #27ae60;
        font-weight: bold;
        margin-bottom: 15px;
    }
    .add-to-cart-form {
        display: flex;
        align-items: center;
    }
    .add-to-cart-form input[type="number"].product-quantity-input {
        width: 60px;
        padding: 8px;
        margin-right: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-align: center;
    }
    .add-button, .clear-cart-button {
        background-color: #3498db;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }
    .add-button:hover, .clear-cart-button:hover {
        background-color: #2980b9;
    }
    .product-unavailable {
        color: #c0392b;
        font-weight: 500;
    }
    .cart {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .cart-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .cart-item-info {
        flex-grow: 1;
    }
    .cart-item-name {
        font-weight: 500;
    }
    .cart-item-quantity {
        font-size: 14px;
        color: #555;
    }
    .cart-item-price {
        font-weight: 600;
        color: #333;
    }
    .cart-summary {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid #ddd;
        font-size: 16px;
        font-weight: 600;
    }
    .cart-summary div {
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
    }
    .clear-cart-button {
        background-color: #e74c3c;
        margin-top: 15px;
        width: 100%;
    }
    .clear-cart-button:hover {
        background-color: #c0392b;
    }
    .empty-cart {
        text-align: center;
        color: #777;
        padding: 20px;
    }
</style>
</body>
</html>