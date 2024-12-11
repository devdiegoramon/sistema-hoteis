<?php
// frigobar/pedido.php

// Função de incluir funções do frigobar
include_once('../func.php');

// Obter o ID do produto da URL
$idProduto = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Verificar se o ID é válido e obter as informações do produto
$produtos = obterProdutosFrigobar();
$produto = null;
foreach ($produtos as $p) {
    if ($p['id'] === $idProduto) {
        $produto = $p;
        break;
    }
}

if (!$produto) {
    die("Produto não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido - Frigobar</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        .product-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .product-details img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-details h3 {
            margin-top: 20px;
        }

        .product-details p {
            color: #555;
            font-size: 1.2em;
        }

        .product-details button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .product-details button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Pedido do Produto</h1>
    
    <div class="product-details">
        <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
        <h3><?php echo $produto['nome']; ?></h3>
        <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
        <form action="pedido_confirmar.php" method="POST">
            <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
            <input type="hidden" name="produto_nome" value="<?php echo $produto['nome']; ?>">
            <input type="hidden" name="produto_preco" value="<?php echo $produto['preco']; ?>">
            <button type="submit">Confirmar Pedido</button>
        </form>
    </div>

</body>
</html>
