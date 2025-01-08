<?php
require_once '../../config/connMysql.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Limpa o carrinho e devolve os itens ao estoque
 */
function limparCarrinho()
{
    global $pdo;

    if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
        try {
            $pdo->beginTransaction();

            foreach ($_SESSION['carrinho'] as $item) {
                $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade + :quantidade WHERE iditem = :iditem");
                $stmt->execute([
                    ':quantidade' => $item['quantidade'],
                    ':iditem' => $item['iditem']
                ]);
            }

            $_SESSION['carrinho'] = [];

            // Remove cookies
            setcookie('carrinho', '', time() - 3600, '/');
            setcookie('cart_timer', '', time() - 3600, '/');

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
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
        setcookie('cart_timer', time() + 60, time() + 60, '/'); // 1 minuto
    }
}

/**
 * Salva o carrinho em um cookie
 */
function salvarCarrinhoEmCookie()
{
    setcookie('carrinho', json_encode($_SESSION['carrinho'], JSON_UNESCAPED_UNICODE), time() + 3600, '/');
}

/**
 * Busca os produtos ativos no estoque com paginação
 *
 * @param int $offset
 * @param int $limit
 * @return array
 */
function buscarProdutosEstoque($offset, $limit)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE ativo = 's' LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar produtos do estoque: " . $e->getMessage());
        return [];
    }
}

/**
 * Adiciona um item ao carrinho
 *
 * @param int $idItem
 * @param int $quantidade
 */
function adicionarAoCarrinho($idItem, $quantidade)
{
    global $pdo;

    if ($quantidade <= 0) {
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE iditem = :iditem AND quantidade >= :quantidade AND ativo = 's'");
        $stmt->execute([
            ':iditem' => $idItem,
            ':quantidade' => $quantidade
        ]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            $encontrado = false;
            foreach ($_SESSION['carrinho'] as &$item) {
                if ($item['iditem'] == $idItem) {
                    $item['quantidade'] += $quantidade;
                    $encontrado = true;
                    break;
                }
            }

            if (!$encontrado) {
                $produto['quantidade'] = $quantidade;
                $_SESSION['carrinho'][] = $produto;
            }

            $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - :quantidade WHERE iditem = :iditem");
            $stmt->execute([':quantidade' => $quantidade, ':iditem' => $idItem]);

            salvarCarrinhoEmCookie();
            setcookie('cart_timer', time() + 60, time() + 60, '/'); // 1 minuto
        }
    } catch (PDOException $e) {
        error_log("Erro ao adicionar ao carrinho: " . $e->getMessage());
    }
}

// Lógica principal
if (isset($_COOKIE['cart_timer']) && time() > $_COOKIE['cart_timer']) {
    limparCarrinho();
}

inicializarCarrinho();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'], $_POST['iditem'], $_POST['quantidade'])) {
        adicionarAoCarrinho((int)$_POST['iditem'], (int)$_POST['quantidade']);
        header("Location: index.php");
        exit;
    }

    if (isset($_POST['limpar_carrinho'])) {
        limparCarrinho();
        echo json_encode(['status' => 'success']);
        exit;
    }
}

$produtos = buscarProdutosEstoque(0, 50);
$quantidadeTotal = 0;
$valorTotal = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $quantidadeTotal += $item['quantidade'];
    $valorTotal += $item['quantidade'] * $item['valorunitario'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frigobar - Painel do Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cartTimer = <?= json_encode($_COOKIE['cart_timer'] ?? 0) ?>;
            let countdown = Math.max(0, cartTimer - Math.floor(Date.now() / 1000));
            const countdownElement = document.getElementById('countdown');

            const timer = setInterval(() => {
                const minutes = Math.floor(countdown / 60);
                const seconds = countdown % 60;
                countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                countdown--;

                if (countdown < 0) {
                    clearInterval(timer);
                    fetch('index.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ limpar_carrinho: true }),
                    }).then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                window.location.reload();
                            }
                        }).catch(console.error);
                }
            }, 1000);
        });
    </script>
</head>
<body>
<div class="container">
    <header class="header">
        <h1 class="title">Itens do Frigobar</h1>
        <div class="timer">
            <span id="countdown">10:00</span>
        </div>
        <div class="cart-icon">
            <a href="#cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?= count($_SESSION['carrinho']) ?></span>
            </a>
        </div>
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
                        <input type="number" name="quantidade" min="1" max="<?= $produto['quantidade'] ?>" value="1" required>
                        <button type="submit" name="add_to_cart" class="add-button">Adicionar</button>
                    </form>
                <?php else: ?>
                    <div class="product-unavailable">Indisponível</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="cart" id="cart">
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
                Total: R$ <?= number_format($valorTotal, 2, ',', '.') ?>
            </div>

            <div class="cart-actions">
                <form action="process_order.php" method="POST">
                    <input type="hidden" name="valor_total" value="<?= $valorTotal ?>">
                    <button type="submit" class="buy-button">Finalizar Compra</button>
                </form>
                <form method="POST">
                    <button type="submit" name="limpar_carrinho" class="clear-button">Limpar Carrinho</button>
                </form>
            </div>
        <?php else: ?>
            <div class="empty-cart">Seu carrinho está vazio</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
