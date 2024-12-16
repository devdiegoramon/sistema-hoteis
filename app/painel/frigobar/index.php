<?php
session_start();

// Inicializar carrinho se não existir no cookie
if (isset($_COOKIE['carrinho'])) {
    $_SESSION['carrinho'] = json_decode($_COOKIE['carrinho'], true);
} else {
    $_SESSION['carrinho'] = $_SESSION['carrinho'] ?? [];
}

// Função para armazenar carrinho em cookie
function saveCartToCookie() {
    setcookie('carrinho', json_encode($_SESSION['carrinho']), time() + 3600, '/'); // 1 hora de validade
}

// Conexão
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

// Função que puxa o estoque
function getStockProducts($offset, $limit) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE ativo = 's' LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Adicionar produto ao carrinho
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
            
            // Salvar o carrinho no cookie
            saveCartToCookie();
            
            header("Location: index.php");
            exit;
        }
    }
}

// Apagar Carrinho
if (isset($_POST['limpar_carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade + :quantidade WHERE iditem = :iditem");
        $stmt->execute([':quantidade' => $item['quantidade'], ':iditem' => $item['iditem']]);
    }
    $_SESSION['carrinho'] = [];
    setcookie('carrinho', '', time() - 3600, '/'); // Remover o cookie
    header("Location: index.php");
    exit;
}

$produtos = getStockProducts(0, 50);

// Total do pedido
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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        // Função para rolar suavemente até o carrinho
        document.addEventListener("DOMContentLoaded", function() {
            const cartLink = document.querySelector('a[href="#cart"]');
            cartLink.addEventListener('click', function(event) {
                event.preventDefault(); // Impede o comportamento padrão de rolar
                document.getElementById('cart').scrollIntoView({ behavior: 'smooth' });
            });

            // Contador de 10 segundos
            let countdown = 10; // 10 segundos
const countdownElement = document.getElementById('countdown');
const timer = setInterval(function() {
    countdownElement.textContent = countdown + 's';
    countdown--;
    if (countdown < 0) {
        clearInterval(timer);

        // cria uma requisição pra apagar o carrinho
        const form = document.createElement('form');
        form.method = 'POST';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'limpar_carrinho';
        input.value = 'true';
        form.appendChild(input);
        
        // Envia o formulário
        document.body.appendChild(form);
        form.submit();
    }
}, 1000);

        });
    </script>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1 class="title">Itens do Frigobar</h1>
            <!-- Contador de tempo no canto superior direito -->
            <div class="timer">
                <span id="countdown">10s</span>
            </div>
            <!-- Carrinho no canto superior direito -->
            <div class="cart-icon">
                <a href="#cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">
                        <?= count($_SESSION['carrinho']) ?> <!-- Exibe a quantidade de itens no carrinho -->
                    </span>
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
                    Total: R$ <?= number_format($valor_total, 2, ',', '.') ?>
                </div>
                
                <div class="cart-actions">
                    <form action="process_order.php" method="POST">
                        <input type="hidden" name="valor_total" value="<?= $valor_total ?>">
                        <button type="submit" class="buy-button">Finalizar Compra</button>
                    </form>
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
    <script>
        // adcionar cookies para resetar ao sair da página
    </script>
</body>
</html>
