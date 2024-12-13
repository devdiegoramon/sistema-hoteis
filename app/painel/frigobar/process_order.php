<?php
session_start();

// CONEXÃO
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

// VE SE O CARRINHO TA VAZIO
if (empty($_SESSION['carrinho'])) {
    die("Erro: O carrinho está vazio.");
}

// VALOR TOTAL DO PEDIDO ENVIADO
$valor_total = (float)$_POST['valor_total'];

// FALTA ATUALIZAR PRA COLHER O ID DO CLIENTE QUE ESTIVER
$idcliente = 1;
$idacomodacao = 1;

// iniciar pedido no bd
try {
    $pdo->beginTransaction(); 

    // pedidos table
    $stmt = $pdo->prepare("INSERT INTO pedidos (data_pedido, status, idcliente, idacomodacao, valor_total) 
                           VALUES (NOW(), 'Pendente', :idcliente, :idacomodacao, :valor_total)");
    $stmt->execute([
        ':idcliente' => $idcliente,
        ':idacomodacao' => $idacomodacao,
        ':valor_total' => $valor_total
    ]);

    $pedido_id = $pdo->lastInsertId(); //get id para solicitação

    // inserir os itens na solicitação
    foreach ($_SESSION['carrinho'] as $item) {
        $stmt = $pdo->prepare("INSERT INTO itensconsumidos (idpedido, iditem, quantidade, valorunitario) 
                               VALUES (:idpedido, :iditem, :quantidade, :valorunitario)");
        $stmt->execute([
            ':idpedido' => $pedido_id,
            ':iditem' => $item['iditem'],
            ':quantidade' => $item['quantidade'],
            ':valorunitario' => $item['valorunitario']
        ]);
    }

    $pdo->commit(); // envia o pedido

    // esvazia o carrinho depois da compra
    $_SESSION['carrinho'] = [];

    // sucesso mensagem
    echo "
    <!DOCTYPE html>
    <html lang='pt-br'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pedido Realizado</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .container {
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            h1 {
                color: #28a745;
            }
            p {
                font-size: 18px;
                color: #333;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
            .btn:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Pedido Realizado com Sucesso!</h1>
            <p>O ID do seu pedido é: <strong>$pedido_id</strong></p>
            <a href='../index.php' class='btn'>Voltar à Página Inicial</a>
        </div>
    </body>
    </html>
    ";
} catch (PDOException $e) {
    $pdo->rollBack(); // retorna caso dê erro
    die("Erro ao processar o pedido: " . $e->getMessage());
}
?>