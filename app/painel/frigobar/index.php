<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque - Painel do Cliente</title>
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
            width: 100%;
            margin-top: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <h1>Itens do Estoque</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Categoria</th>
                    <th>Quantidade</th>
                    <th>Preço (Unitário)</th>
                    <th>Status</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Incluir o arquivo de funções
                include_once('func.php');

                // Definir o número de itens por página
                $itens_por_pagina = 10;

                // Calcular o número da página atual
                $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

                // Calcular o offset para a consulta SQL
                $offset = ($pagina_atual - 1) * $itens_por_pagina;

                // Obter os produtos do estoque com paginação
                $produtos = obterProdutosEstoque($offset, $itens_por_pagina);

                if (empty($produtos)) {
                    echo "<tr><td colspan='7'>Nenhum produto encontrado no estoque.</td></tr>";
                } else {
                    foreach ($produtos as $produto) {
                        echo "<tr>";
                        echo "<td>{$produto['item']}</td>";
                        echo "<td>{$produto['categoria']}</td>";
                        echo "<td>{$produto['quantidade']}</td>";
                        echo "<td>R$ {$produto['valorunitario']}</td>";
                        echo "<td>" . ($produto['ativo'] === 'sim' ? 'Ativo' : 'Inativo') . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

   
</body>
</html>
