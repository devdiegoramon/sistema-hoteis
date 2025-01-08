<?php

$pdo = null;
// Conectar ao banco de dados
$con = mysqli_connect('localhost', 'root', '', 'sistema_hoteis_prosync');

// Verificar se a conexão foi bem-sucedida
if (!$con) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

// Configurar o charset para UTF-8
mysqli_set_charset($con, 'utf8');

// Pronto! A conexão está ativa e configurada para UTF-8.
