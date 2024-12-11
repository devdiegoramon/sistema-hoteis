<?php
// func.php

function obterProdutosEstoque($offset, $limit) {
    $conn = new mysqli("localhost", "admin", "", "sistema_hoteis_prosync");

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Ajuste a consulta para pegar os produtos com paginação
    $sql = "SELECT iditem, item, categoria, quantidade, valorunitario, ativo FROM estoque WHERE ativo = 'sim' LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $produtos;
}

// Função para contar o total de produtos
function contarProdutosEstoque() {
    $conn = new mysqli("localhost", "admin", "sua_senha_aqui", "sistema_hoteis_prosync");

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $sql = "SELECT COUNT(*) AS total FROM estoque WHERE ativo = 'sim'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $conn->close();

    return $row['total'];
}


?>
