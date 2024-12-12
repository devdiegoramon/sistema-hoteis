<?php
session_start();  // Inicia a sessão

// Verifica se o carrinho já existe, se não, cria um
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = array();
}

// Conexão com o banco de dados
$servidor = "localhost";
$usuario = "admin";
$senha = "";
$banco = "sistema_hoteis_prosync";

$con = new mysqli($servidor, $usuario, $senha, $banco);

if ($con->connect_error) {
    die("Erro de conexão: " . $con->connect_error);
}

// Função para obter os produtos do estoque
function obterProdutosEstoque($offset, $limit) {
    global $con;
    // Alterei a consulta SQL para pegar apenas produtos com estoque maior que 0
    $sql = "SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE ativo = 's' AND quantidade > 0 LIMIT {$limit} OFFSET {$offset}";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        return $result;
    }
    return false;
}

// Lógica para adicionar produto ao carrinho
if (isset($_GET['id']) && isset($_POST['quantidade'])) {
    $iditem = (int)$_GET['id'];
    $quantidade = (int)$_POST['quantidade'];

    // Verifica se a quantidade é maior que 0
    if ($quantidade > 0) {
        // Consulta o produto pelo ID
        $sql = "SELECT iditem, item, categoria, valorunitario, quantidade FROM estoque WHERE iditem = {$iditem}";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $produto = mysqli_fetch_assoc($result);

            // Verifica se a quantidade no estoque é suficiente
            if ($produto['quantidade'] >= $quantidade) {
                // Verifica se o produto já está no carrinho
                $existe = false;
                foreach ($_SESSION['carrinho'] as $key => $item) {
                    if ($item['iditem'] == $iditem) {
                        // Atualiza a quantidade do produto no carrinho
                        $_SESSION['carrinho'][$key]['quantidade'] += $quantidade;
                        $existe = true;
                        break;
                    }
                }

                // Se o produto não estiver no carrinho, adiciona um novo item
                if (!$existe) {
                    $produto['quantidade'] = $quantidade;  // Inicializa a quantidade com a selecionada
                    $_SESSION['carrinho'][] = $produto;
                }

                // Atualizar quantidade do produto no estoque
                $quantidade_adicionada = (int)$quantidade;  // Quantidade que o usuário deseja adicionar ao carrinho
                $id_item = (int)$iditem;  // ID do produto

                // Atualiza o estoque
                $update_sql = "UPDATE estoque SET quantidade = quantidade - " . $quantidade_adicionada . " WHERE iditem = " . $id_item;
                if (mysqli_query($con, $update_sql)) {
                    // Redireciona para evitar a duplicação da adição ao carrinho
                    header("Location: index.php");
                    exit;
                } else {
                    echo "Erro ao atualizar estoque.";
                }

            } else {
                echo "Quantidade solicitada maior do que a disponível em estoque.";
            }
        } else {
            echo "Produto não encontrado.";
        }
    } else {
        echo "Selecione uma quantidade maior que 0.";
    }
}

// Função para limpar o carrinho e devolver as quantidades ao estoque
if (isset($_POST['limpar_carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $quantidade_restaurada = $item['quantidade'];
        $iditem = (int)$item['iditem'];

        // Atualiza o estoque
        $update_sql = "UPDATE estoque SET quantidade = quantidade + {$quantidade_restaurada} WHERE iditem = {$iditem}";
        mysqli_query($con, $update_sql);
    }

    // Limpa o carrinho
    unset($_SESSION['carrinho']);
    header("Location: index.php");  // Redireciona para evitar reenvio do formulário
    exit;
}

// Exibindo os produtos
$itens_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $itens_por_pagina;

$produtos = obterProdutosEstoque($offset, $itens_por_pagina);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frigobar - Painel do Cliente</title>
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

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .cart-summary {
            margin-top: 30px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-summary p {
            font-size: 18px;
            font-weight: bold;
        }

        .cart-summary button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%; /* Para ocupar a largura total do celular */
            text-align: center;
            transition: background-color 0.3s ease; /* Efeito suave ao passar o mouse */
        }

        .cart-summary button:hover {
            background-color: #218838;
        }

        .cart-items {
            margin-top: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        form input[type="number"] {
            width: 40%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        form button {
            width: 40%;
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        form button:active {
            background-color: #003d80;
        }

        /* Espaço entre os botões */
        .cart-summary button + button {
            margin-top: 15px;
        }

        /* Media Query para telas menores */
        @media (max-width: 600px) {
            table, .cart-summary {
                padding: 10px;
            }

            h1 {
                font-size: 24px;
            }

            form input[type="number"] {
                width: 100%;
            }

            form button {
                width: 100%; /* Para o botão ocupar toda a largura da tela em dispositivos móveis */
                font-size: 20px;
            }

            .pagination a {
                padding: 12px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <h1>Itens do Frigobar</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($produtos) {
                    while ($produto = mysqli_fetch_assoc($produtos)) {
                        $quantidade_estoque = $produto['quantidade'];
                        echo "<tr>";
                        echo "<td>{$produto['item']}</td>";
                        echo "<td>{$produto['categoria']}</td>";
                        echo "<td>R$ " . number_format($produto['valorunitario'], 2, ',', '.') . "</td>";
                        echo "<td>";

                        // Verificando a disponibilidade do estoque
                        if ($quantidade_estoque > 0) {
                            echo "<form method='POST' action='index.php?id={$produto['iditem']}'>
                                    <input type='number' name='quantidade' min='1' max='{$quantidade_estoque}' value='1' required>
                                    <button type='submit'>Adicionar</button>
                                  </form>";
                        } else {
                            echo "<span>Indisponível</span>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhum produto disponível.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="cart-summary">
        <h3>Carrinho</h3>
        <?php
        $quantidade_total = 0;
        $valor_total = 0;

        if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0) {
            echo "<div class='cart-items'>";
            foreach ($_SESSION['carrinho'] as $item) {
                $quantidade_total += $item['quantidade'];
                $valor_total += $item['quantidade'] * $item['valorunitario'];
                echo "<p>{$item['item']} - Quantidade: {$item['quantidade']} - R$ " . number_format($item['valorunitario'], 2, ',', '.') . " x {$item['quantidade']}</p>";
            }
            echo "</div>";

            echo "<p>Itens no carrinho: {$quantidade_total}</p>";
            echo "<p>Total: R$ " . number_format($valor_total, 2, ',', '.') . "</p>";
            echo "<button>Comprar</button>";
            
            // Botão de limpar carrinho com margem superior
            echo "<form method='POST' action=''>
                    <button type='submit' name='limpar_carrinho'>Limpar Carrinho</button>
                  </form>";
        } else {
            echo "<p>Seu carrinho está vazio.</p>";
        }
        ?>
    </div>
</body>
</html>
